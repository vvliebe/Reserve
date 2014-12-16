<div class="col-sm-3 user">
    <div id="unlogin" class="login-div @if($login=="true") hide @endif">
        <div class="user-title">
            <span class="glyphicon glyphicon-user"></span> 新用户注册
        </div>
        <div class="hr"></div>
        <div class="user-body">
            <button class="user-register btn btn-info btn-lg">用户注册</button>
            <button class="doctor-register btn btn-default btn-lg">医生注册</button>
            <div class="user-login">已经有帐号了？直接<button data-toggle="modal" href="#login-modal" class="btn btn-link">登录</button></div>
        </div>
    </div>
    <div id="login" class="login-div @if($login=="false") hide @endif">
        <div class="user-title">
            <span class="glyphicon glyphicon-user"></span> 您已登陆
        </div>
    </div>

</div>