<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <!--
    Customize this policy to fit your own app's needs. For more guidance, see:
        https://github.com/apache/cordova-plugin-whitelist/blob/master/README.md#content-security-policy
    Some notes:
        * gap: is required only on iOS (when using UIWebView) and is needed for JS->native communication
        * https://ssl.gstatic.com is required only on Android and is needed for TalkBack to function properly
        * Disables use of inline scripts in order to mitigate risk of XSS vulnerabilities. To change this:
            * Enable inline JS: add 'unsafe-inline' to default-src
    -->
    <meta http-equiv="Content-Security-Policy" content="default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap: content:">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, minimum-scale=1, user-scalable=no, minimal-ui, viewport-fit=cover">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="theme-color" content="#2196f3">
    <meta name="format-detection" content="telephone=no">
    <meta name="msapplication-tap-highlight" content="no">
    <title>CamChain</title>

    <link rel="stylesheet" href="framework7/css/framework7.min.css">
    <link rel="stylesheet" href="css/icons.css">
    <link rel="stylesheet" href="css/app.css">
    <link href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css" rel="stylesheet">

    <style>
        .demo-facebook-card .card-header {
            display: block;
            padding: 10px;
        }
        .demo-facebook-card .demo-facebook-avatar {
            float: left;
        }
        .demo-facebook-card .demo-facebook-name {
            margin-left: 44px;
            font-size: 14px;
            font-weight: 500;
        }
        .demo-facebook-card .demo-facebook-date {
            margin-left: 44px;
            font-size: 13px;
            color: #8e8e93;
        }
        .demo-facebook-card .card-footer {
            background: #fafafa;
        }
        .demo-facebook-card .card-footer a {
            color: #81848b;
            font-weight: 500;
        }
        .demo-facebook-card .card-content img {
            display: block;
        }
        .demo-facebook-card .card-content-padding {
            padding: 15px 10px;
        }
        .demo-facebook-card .card-content-padding .likes {
            color: #8e8e93;
        }
    </style>
</head>
<body>
<div id="app">
    <!-- Status bar overlay for fullscreen mode-->
    <div class="statusbar"></div>
    <!-- Left panel with reveal effect when hidden -->
    <div class="panel panel-left panel-reveal">
        <div class="view view-left">
            <div class="page">
                <div class="navbar">
                    <div class="navbar-inner sliding">
                        <div class="title">Menu</div>
                    </div>
                </div>
                <div class="page-content">
                    <div class="block-title">菜单</div>
                    <div class="list links-list">
                        <ul>
                            <li><a href="/" data-view=".view-main" data-ignore-cache="true" class="panel-close">首页</a></li>
                            <li><a href="/user/{{ \Illuminate\Support\Facades\Auth::user() == null ? "" : \Illuminate\Support\Facades\Auth::user()->name }}" data-view=".view-main" data-ignore-cache="true" class="panel-close">我的</a></li>
                            <li><a href="/upload" data-view=".view-main" data-ignore-cache="true" class="panel-close">上传</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Your main view, should have "view-main" class -->
    <div class="view view-main ios-edges">
        <!-- Page, data-name contains page name which can be used in callbacks -->
        <div class="page" data-name="home">
            <!-- Top Navbar -->
            <div class="navbar">
                <div class="navbar-inner">
                    <div class="left">
                        <a href="#" class="link icon-only panel-open" data-panel="left">
                            <i class="icon f7-icons ios-only">menu</i>
                            <i class="icon material-icons md-only">menu</i>
                        </a>
                    </div>
                    <div class="title sliding">CamChain</div>
                </div>
            </div>
            <!-- Scrollable page content-->
            <div class="page-content">
                <div class="row">
                    @foreach ($buckets as $bucket)
                        <div class="card demo-facebook-card desktop-33 col-100">
                            <div class="card-header">
                                <div class="demo-facebook-avatar"><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eprBvgOthOlFVR5GkNwxxzFEVTUafU0kk1fM99WnWWe1hDspMsha9kykWF0qzKDVD1OJPP6N5RNrg/132" width="34" height="34"/></div>
                                <div class="demo-facebook-name">{{ $bucket->name }}</div>
                                <div class="demo-facebook-date">{{ ($bucket->cover)["created"] }}</div>
                            </div>
                            <div class="card-content"> <img src="{{ ($bucket->cover)["picture"] }}" width="100%"/></div>
                            <div class="card-footer"><a href="/user/{{ $bucket->name }}" class="link">View all</a></div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>

    <!-- Login Screen -->
    <div class="login-screen" id="my-login-screen">
        <div class="view">
            <div class="page">
                <div class="page-content login-screen-content">
                    <div class="login-screen-title">欢迎</div>
                    <div class="list">
                        <ul>
                            <li class="item-content item-input">
                                <div class="item-inner">
                                    <div class="item-title item-label">用户名</div>
                                    <div class="item-input-wrap">
                                        <input type="text" name="username" placeholder="Username">
                                    </div>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="list">
                        <ul>
                            <li>
                                <a href="#" class="item-link list-button login-button">注册</a>
                            </li>
                        </ul>
                        <div class="block-footer">您似乎是第一次访问<br>您可以先创建一个账户</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Cordova -->
<!--
<script src="cordova.js"></script>
-->
<script src="js/client.min.js"></script>

<!-- Framework7 library -->
<script src="framework7/js/framework7.min.js"></script>

<!-- App routes -->
<script src="js/routes.js"></script>

<script src="https://cdn.bootcss.com/jquery/3.3.1/jquery.min.js"></script>
<script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>

<!-- Your custom app scripts -->
<script src="js/app.js"></script>
</body>
</html>
