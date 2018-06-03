<div class="page" data-name="user">
    <div class="navbar">
        <div class="navbar-inner sliding">
            <div class="left">
                <a href="#" class="link back">
                    <i class="icon icon-back"></i>
                    <span class="ios-only">Back</span>
                </a>
            </div>
            <div class="title">{{ $user->name }}</div>
        </div>
    </div>
    <div class="page-content">
        <div class="block-title">All Photos</div>
        <div class="row">
            @foreach ($files as $file)
                <div class="card demo-facebook-card col-100 desktop-30">
                    <div class="card-header">
                        <div class="demo-facebook-avatar"><img src="http://thirdwx.qlogo.cn/mmopen/vi_32/DYAIOgq83eprBvgOthOlFVR5GkNwxxzFEVTUafU0kk1fM99WnWWe1hDspMsha9kykWF0qzKDVD1OJPP6N5RNrg/132" width="34" height="34"/></div>
                        <div class="demo-facebook-name">{{ $user->name }}</div>
                        <div class="demo-facebook-date">{{ $file["created"] }}</div>
                    </div>
                    <div class="card-content card-content-padding">
                        <p>{!! $file["msg"] !!}</p>
                        <img src="{{ $file["picture"] }}" width="100%"/>
                    </div>
                    <div class="card-footer"><a href="#" class="link">Like</a><a href="#" class="link">Comment</a><a href="#" class="link">Share</a></div>
                </div>
            @endforeach
        </div>
    </div>
</div>
