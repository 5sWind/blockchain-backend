<div class="page" data-name="upload">
    <div class="navbar">
        <div class="navbar-inner sliding">
            <div class="left">
                <a href="#" class="link back">
                    <i class="icon icon-back"></i>
                    <span class="ios-only">Back</span>
                </a>
            </div>
            <div class="title">上传图片</div>
        </div>
    </div>
    <div class="page-content">
        <div class="block-title">* 上传图片仅供测试，事实上我们只支持使用我们的树莓派设备进行上传</div>
        <form class="list" id="my-form">
            <ul>
                <li>
                    <div class="item-content item-input">
                        <div class="item-inner">
                            <div class="item-title item-label">图片</div>
                            <div class="item-input-wrap">
                                <input name="picture" type="file" id="picture" accept="image/*">
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
            <ul>
                <li>
                    <div class="item-content item-input">
                        <div class="item-inner">
                            <div class="item-title item-label">信息</div>
                            <div class="item-input-wrap">
                                <input type="text" name="msg" id="msg" placeholder="任意信息">
                            </div>
                        </div>
                    </div>
                </li>
            </ul>
        </form>
        <div class="block block-strong row">
            <div class="col"><a id="upload_btn" class="button" href="#">上传</a></div>
        </div>
    </div>
</div>
