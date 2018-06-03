<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Response;
use Ixudra\Curl\Facades\Curl;

class AuthController extends Controller
{
    public function login(Request $request) {
        $data = Input::all();

        if (User::where("email", $data["fingerprint"])->count()) {
            if (Auth::attempt(array(
                "email" => $data["fingerprint"],
                "password" => $data["fingerprint"],
            ))) {
                return Response::json(array(
                    "success" => true,
                ));
            } else {
                return Response::json(array(
                    "success" => false,
                    "msg" => "未知错误"
                ));
            }
        } else {
            return Response::json(array(
                "success" => false,
                "msg" => "需要注册",
            ));
        }
    }

    public function signup(Request $request)
    {
        $data = Input::all();

        if (User::where("name", $data["username"])->count()) {
            return Response::json(array(
                "success" => false,
                "msg" => "用户名已被注册",
            ));
        }
        $user = new User(array(
            "name" => $data["username"],
            "email" => $data["fingerprint"],
            "password" => Hash::make($data["fingerprint"]),
        ));
        $user->save();

        $response = Curl::to(env("API_SERVER") . "bucket/create/naive_{$data["username"]}")
            ->get();
        $files = json_decode($response);

        if (Auth::attempt(array(
            "email" => $data["fingerprint"],
            "password" => $data["fingerprint"],
        ))) {
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
