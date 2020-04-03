<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{!! site()['siteWebName'] !!}</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="apple-mobile-web-app-status-bar-style" content="black">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="format-detection" content="telephone=no">
    <link rel="stylesheet" href="/resource/css/oksub.css" media="all"/>
    <script type="text/javascript" src="/resource/lib/loading/okLoading.js"></script>
    <script type="text/javascript" src="/resource/lib/echarts/echarts.min.js"></script>
    <script type="text/javascript" src="/resource/lib/echarts/echarts.themez.js"></script>
</head>
<body class="console console1 ok-body-scroll">
<div class="ok-body home">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-xs6 layui-col-md3">
            <div class="ok-card layui-card">
                <div class="ok-card-body p0 clearfix cart-data">
                    <div class="data-body">
                        <div class="media-cont">
                            <p class="tit">收入</p>
                            <h5 class="num">0</h5>
                        </div>
                        <div class="w-img" ok-pc-in-show>
                            <img src="/resource/images/home-01.png" alt="收入"/>
                        </div>
                    </div>
                    <div id="echIncome" class="line-home-a"></div>
                </div>
            </div>
        </div>

        <div class="layui-col-xs6 layui-col-md3">
            <div class="ok-card layui-card">
                <div class="ok-card-body p0 clearfix cart-data">
                    <div class="data-body">
                        <div class="media-cont">
                            <p class="tit">商品</p>
                            <h5 class="num">0</h5>
                        </div>
                        <div class="w-img" ok-pc-in-show>
                            <img src="/resource/images/home-02.png" alt="商品"/>
                        </div>
                    </div>
                    <div id="echGoods" class="line-home-a"></div>
                </div>
            </div>
        </div>

        <div class="layui-col-xs6 layui-col-md3">
            <div class="ok-card layui-card">
                <div class="ok-card-body p0 clearfix cart-data">
                    <div class="data-body">
                        <div class="media-cont">
                            <p class="tit">博客</p>
                            <h5 class="num">0</h5>
                        </div>
                        <div class="w-img" ok-pc-in-show>
                            <img src="/resource/images/home-03.png" alt="博客"/>
                        </div>
                    </div>
                    <div id="echBlogs" class="line-home-a"></div>
                </div>
            </div>
        </div>

        <div class="layui-col-xs6 layui-col-md3">
            <div class="ok-card layui-card">
                <div class="ok-card-body p0 clearfix cart-data">
                    <div class="data-body">
                        <div class="media-cont">
                            <p class="tit">用户</p>
                            <h5 class="num">0</h5>
                        </div>
                        <div class="w-img" ok-pc-in-show>
                            <img src="/resource/images/home-04.png" alt="用户"/>
                        </div>
                    </div>
                    <div id="echUser" class="line-home-a"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="layui-row layui-col-space15">
        <div class="layui-col-md8">
            <div class="layui-card">
                <div class="layui-card-header">
                    <div class="ok-card-title">今日用户活跃量</div>
                </div>
                <div class="ok-card-body map-body">
                    <div style="height: 100%;" id="userActiveTodayChart"></div>
                </div>
            </div>
        </div>

        <div class="layui-col-md4">
            <div class="layui-card">
                <div class="layui-card-header">
                    <div class="ok-card-title">今日用户访问来源</div>
                </div>
                <div class="ok-card-body map-body">
                    <div style="height: 100%;" id="userSourceTodayChart"></div>
                </div>
            </div>
        </div>
    </div>

    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-header">
                    <div class="ok-card-title">本周用户访问来源</div>
                </div>
                <div class="ok-card-body clearfix">
                    <div class="map-china" id="userSourceWeekChart"></div>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<script type="text/javascript" src="/resource/lib/layui/layui.js"></script>
<script type="text/javascript" src="/resource/js/console1.js"></script>



















