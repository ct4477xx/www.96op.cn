<!DOCTYPE html>
<html lang="en" class="page-fill">
<head>
    <meta charset="UTF-8">
    <title>{!! site()['siteWebName'] !!}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="keywords" content="{!! site()['keywords'] !!}">
    <meta name="description" content="{!! site()['description'] !!}">
    <link rel="shortcut icon" href="{!! site()['ico'] !!}" type="image/x-icon"/>
    @include('sys.public.css')
</head>
<body class="layui-layout-body">
<!-- 更换主体 Eg:orange_theme|blue_theme -->
<div class="layui-layout layui-layout-admin okadmin blue_theme">
    <!--头部导航-->
@include('sys.public.head')
<!--遮罩层-->
    <div class="ok-make"></div>
    <!--左侧导航区域-->
@include('sys.public.left')

<!-- 内容主体区域 -->
    <div class="content-body">
        <div class="layui-tab ok-tab" lay-filter="ok-tab" lay-allowClose="true" lay-unauto>
            <div data-id="left" id="okLeftMove"
                 class="ok-icon ok-icon-back okadmin-tabs-control move-left okNavMove"></div>
            <div data-id="right" id="okRightMove"
                 class="ok-icon ok-icon-right okadmin-tabs-control move-right okNavMove"></div>
            <div class="layui-icon okadmin-tabs-control ok-right-nav-menu" style="right: 0;">
                <ul class="okadmin-tab">
                    <li class="no-line okadmin-tab-item">
                        <div class="okadmin-link layui-icon-down" href="javascript:;"></div>
                        <dl id="tabAction" class="okadmin-tab-child layui-anim-upbit layui-anim">
                            <dd><a data-num="1" href="javascript:">关闭当前标签页</a></dd>
                            <dd><a data-num="2" href="javascript:">关闭其他标签页</a></dd>
                            <dd><a data-num="3" href="javascript:">关闭所有标签页</a></dd>
                        </dl>
                    </li>
                </ul>
            </div>

            <ul id="tabTitle" class="layui-tab-title ok-tab-title not-scroll">
                <li class="layui-this" lay-id="1" tab="index">
                    <i class="ok-icon">&#xe654;</i>
                    <cite is-close=false>控制台</cite>
                </li>
            </ul>

            <div id="tabContent" class="layui-tab-content ok-tab-content">
                <div class="layui-tab-item layui-show">
                    <iframe src='pages/console' frameborder="0" scrolling="yes" width="100%" height="100%"></iframe>
                </div>
            </div>

        </div>
    </div>

    <!--底部信息-->
    @include('sys.public.foot')
</div>
<div class="yy"></div>
<!--js逻辑-->
@include('sys.public.script')
</body>
</html>
