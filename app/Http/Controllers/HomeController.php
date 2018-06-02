<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Ixudra\Curl\Facades\Curl;

class HomeController extends Controller
{
    public function getFile($bucket_id, $file_id)
    {
        $response = Curl::to(env("API_SERVER") . "file/{$bucket_id}/{$file_id}")
            ->get();
        return json_decode($response);
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
            file_put_contents("storage/json/{$bucket_id}/{$file_id}.json", base64_decode($file->data));
        }
        return ["image" => "storage/images/{$bucket_id}/{$file_id}.jpg", "data" => "storage/json/{$bucket_id}/{$file_id}.json"];
    }

    public function getAllBuckets()
    {
        $response = Curl::to(env("API_SERVER") . "bucket/all")
            ->get();
        return json_decode($response);
    }

    public function getBucketOneFile($bucket_id)
    {
        $response = Curl::to(env("API_SERVER") . "file/{$bucket_id}")
            ->get();
        $files = json_decode($response);

        if (count($files) > 0) {
            $cache = $this->cacheImage($bucket_id, $files[0]->id);
            $picture = $cache["image"];
            $data = file_get_contents($cache["data"]);
            return [
                "picture" => $picture,
                "data" => $data,
            ];
        } else {
            return false;
        }

    }

    public function welcome() {
        $buckets = $this->getAllBuckets();

        foreach ($buckets as $index => $bucket) {
            $cover = $this->getBucketOneFile($bucket->id);
            if ($cover == false) {
                unset($buckets[$index]);
            }
            $bucket->cover = $cover;
        }

        return view("welcome")->with([
            "buckets" => $buckets,
        ]);
    }
}
