<div class="layui-header okadmin-header">
    <ul class="layui-nav layui-layout-left">
        <li class="layui-nav-item">
            <a class="ok-menu ok-show-menu" href="javascript:" title="菜单切换">
                <i class="layui-icon layui-icon-shrink-right"></i>
            </a>
        </li>
        <!--天气信息-->
        <li class="ok-nav-item ok-hide-md">
            <div class="weather-ok">
                <iframe frameborder="0" scrolling="no" class="iframe-style" src="sys/pages/weather"
                        frameborder="0"></iframe>
            </div>
        </li>
    </ul>
    <ul class="layui-nav layui-layout-right">
        <li class="layui-nav-item ok-input-search">
            <input type="text" placeholder="搜索..." class="layui-input layui-input-search"/>
        </li>
        <li class="layui-nav-item">
            <a class="ok-refresh" href="javascript:" title="刷新">
                <i class="layui-icon layui-icon-refresh-3"></i>
            </a>
        </li>
        <li class="no-line layui-nav-item layui-hide-xs">
            <a id="notice" class="flex-vc pr10 pl10" href="javascript:">
                <i class="ok-icon ok-icon-notice icon-head-i" title="系统公告"></i>
                <span class="layui-badge-dot"></span>
                <cite></cite>
            </a>
        </li>

        <li class="no-line layui-nav-item layui-hide-xs">
            <a id="lock" class="flex-vc pr10 pl10" href="javascript:">
                <i class="ok-icon ok-icon-lock icon-head-i" title="锁屏"></i><cite></cite>
            </a>
        </li>

        <!-- 全屏 -->
        <li class="layui-nav-item layui-hide-xs">
            <a id="fullScreen" class=" pr10 pl10" href="javascript:">
                <i class="layui-icon layui-icon-screen-full"></i>
            </a>
        </li>

        <li class="no-line layui-nav-item">
            <a href="javascript:">
                <img src="/resource/images/avatar.png" class="layui-nav-img">
                {{\Cookie::get('admName')}}
            </a>
            <dl id="userInfo" class="layui-nav-child">
                <dd><a lay-id="u-1" href="javascript:" data-url="/sys/userInfo">基本资料</a></dd>
                <dd><a lay-id="u-2" href="javascript:" data-url="/sys/userPwd">安全设置</a></dd>
                <dd><a lay-id="u-3" href="javascript:" id="alertSkin">皮肤动画<span class="layui-badge-dot"></span></a></dd>
                <dd>
                    <hr/>
                </dd>
                <dd><a href="javascript:void(0)" id="logout">退出登录</a></dd>
            </dl>
        </li>
    </ul>
</div>
