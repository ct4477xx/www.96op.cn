<div class="layui-side layui-side-menu okadmin-bg-20222A ok-left">
    <div class="layui-side-scroll okadmin-side">
        <div class="okadmin-logo">{!! site()['title'] !!}</div>
        <div class="user-photo">
            <a class="img" title="我的头像">
                <img src="/images/avatar.png" class="userAvatar">
            </a>
            <p>你好！<span class="userName">{{_admName()}}</span>, 欢迎登录</p>
        </div>
        <!--左侧导航菜单-->
        <ul id="navBar" class="layui-nav okadmin-nav okadmin-bg-20222A layui-nav-tree">
            {{--            <li class="layui-nav-item layui-this">--}}
            {{--                <a href="javascript:" lay-id="1" data-url="pages/console">--}}
            {{--                    <i is-close=false class="ok-icon">&#xe654;</i>--}}
            {{--                    控制台--}}
            {{--                </a>--}}
            {{--            </li>--}}
        </ul>
    </div>
</div>
