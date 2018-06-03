<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Input;
use Ixudra\Curl\Facades\Curl;
use Carbon\Carbon;

class HomeController extends Controller
{
    public function getBucketIdByUser($username)
    {
        $response = Curl::to(env("API_SERVER") . "bucket/all")
            ->get();
        $response = json_decode($response);

        foreach ($response as $user) {
            if ($user->name == "naive_" . $username) {
                return $user;
            }
        }

        return false;
    }

    public function getFile($bucket_id, $file_id)
    {
        $response = Curl::to(env("API_SERVER") . "file/{$bucket_id}/{$file_id}")
            ->get();

        $allfile = Curl::to(env("API_SERVER") . "file/{$bucket_id}")
            ->get();
        $files = json_decode($allfile);

        $response = json_decode($response);

        foreach ($files as $file) {
            if ($file->id == $file_id) {
                $response->created = $file->created;
            }
        }

        return $response;
    }

    public function cacheImage($bucket_id, $file_id)
    {
        if (!file_exists(public_path("storage/images/{$bucket_id}"))) {
            mkdir(public_path("storage/images/{$bucket_id}"));
        }
        if (!file_exists(public_path("storage/json/{$bucket_id}"))) {
            mkdir(public_path("storage/json/{$bucket_id}"));
        }
        if (!file_exists(public_path("storage/images/{$bucket_id}/{$file_id}.jpg"))) {
            $file = $this->getFile($bucket_id, $file_id);
            file_put_contents("storage/images/{$bucket_id}/{$file_id}.jpg", base64_decode($file->filedata));
            file_put_contents("storage/json/{$bucket_id}/{$file_id}.json", json_encode(["data" => $file->data, "created" => $file->created]));
        }
        return ["image" => "storage/images/{$bucket_id}/{$file_id}.jpg", "data" => "storage/json/{$bucket_id}/{$file_id}.json"];
    }

    public function getAllBuckets()
    {
        $response = Curl::to(env("API_SERVER") . "bucket/all")
            ->get();
        $response = json_decode($response);
        foreach ($response as $index => $bucket) {
            if (substr($bucket->name, 0, 5) != "naive") {
                unset($response[$index]);
            }
        }
        return $response;
    }

    public function getBucketOneFile($bucket_id)
    {
        $response = Curl::to(env("API_SERVER") . "file/{$bucket_id}")
            ->get();
        $files = json_decode($response);

        if (count($files) > 0) {
            $cache = $this->cacheImage($bucket_id, $files[count($files) - 1]->id);
            $picture = $cache["image"];
            $data = file_get_contents($cache["data"]);
            return [
                "picture" => $picture,
                "data" => json_decode($data)->data,
                "created" => json_decode($data)->created,
            ];
        } else {
            return false;
        }

    }

    public function getBucketFiles($bucket_id)
    {
        $response = Curl::to(env("API_SERVER") . "file/{$bucket_id}")
            ->get();
        $files = json_decode($response);
        $ret = [];

        foreach ($files as $file) {
            $cache = $this->cacheImage($bucket_id, $file->id);
            $picture = $cache["image"];
            $data = file_get_contents($cache["data"]);
            $decode = json_decode($data);

            if (isset($decode->data->monoxide)) {
                $decode->data->humidity = $decode->data->humidity / 10;
                $msg = "一氧化碳: {$decode->data->monoxide} ppb<br />温度: {$decode->data->temperature}°C<br />湿度: {$decode->data->humidity}%<br />光强: {$decode->data->light} cd";
            } elseif (isset($decode->data->msg)) {
                $msg = $decode->data->msg;
            } else {
                $msg = json_encode($decode->data);
            }
            $ret[] = [
                "picture" => $picture,
                "data" => json_decode($data)->data,
                "msg" => $msg,
                "created" => json_decode($data)->created,
            ];
        }

        return $ret;

    }

    public function welcome() {
        $buckets = $this->getAllBuckets();

        foreach ($buckets as $index => $bucket) {
            $cover = $this->getBucketOneFile($bucket->id);
            if ($cover == false) {
                unset($buckets[$index]);
            }
            $cover["created"] = Carbon::parse($cover["created"])->diffForHumans();
            $bucket->cover = $cover;
            $bucket->name = substr($bucket->name, 6);
        }

        return view("welcome")->with([
            "buckets" => $buckets,
        ]);
    }


    public function getUserPage(Request $request) {
        $username = $request->username;

        $bucket = $this->getBucketIdByUser($username);

        if ($bucket == false) {
            die("Oops");
        } else {
            $files = $this->getBucketFiles($bucket->id);
            $bucket->name = substr($bucket->name, 6);

            foreach ($files as $index => $file) {
                $file["created"] = Carbon::parse($file["created"])->diffForHumans();
            }

            return view("user")->with([
                "user" => $bucket,
                "files" => $files,
            ]);
        }
    }

    public function upload() {
        $data = Input::all();

        $filedata = $data["filedata"];
        $data = $data["data"];
        $bucket = $this->getBucketIdByUser(Auth::user()->name)->id;

        if (strlen($filedata) > 1000) {
            return Response::json(array(
                "success" => false,
                "msg" => "文件过大",
            ));
        }

        $response = Curl::to(env("API_SERVER") . "file/upload")
            ->withData( array(
                'filedata' => $filedata,
                'data' => json_encode(["msg" => $data]),
                'bucket' => $bucket,
            ) )
            ->asJson()
            ->post();

        if ($response->success == true) {
            return Response::json(array(
                "success" => true,
            ));
        } else {
            return Response::json(array(
                "success" => false,
                "msg" => "内部错误，请稍后再试",
            ));
        }

    }
}
