<!DOCTYPE html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>{!! home()['title'] !!}}</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    @include('.sys.pubilc.css')
    @include('.sys.pubilc.js')
</head>

<body>
<div class="x-nav">
            <span class="layui-breadcrumb">
                <a>首页</a>
                <a>租房管理</a>
                <a><cite>房间管理</cite></a>
            </span>
    <a class="layui-btn layui-btn-small" style="line-height:1.6em;margin-top:3px;float:right"
       onclick="location.reload()" title="刷新">
        <i class="layui-icon layui-icon-refresh" style="line-height:30px"></i>
    </a>
</div>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="post" action="{{url('sys/house')}}">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="楼层" name="cate_name"></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon"></i>增加
                            </button>
                        </div>
                        <input type="hidden" name="bySort" value="0">
                        {{csrf_field()}}
                    </form>
                    {{--                    <hr>--}}
                    {{--                    <blockquote class="layui-elem-quote">每个tr 上有两个属性 cate-id='1' 当前分类id fid='0' 父级id ,顶级分类为--}}
                    {{--                        0，有子分类的前面加收缩图标<i class="layui-icon x-show" status='true'>&#xe623;</i></blockquote>--}}
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form" lay-filter="test">
                        <thead>
                        <tr>
                            <th width="50">ID</th>
                            <th width="350">楼层</th>
                            <th width="50">排序</th>
                            <th width="80">状态</th>
                            <th width="250">操作</th>
                        </thead>
                        <tbody class="x-cate">
                        @foreach($data as $v)
                            <tr cate-id='{!! $v['id'] !!}' fid='{!! $v['fatherId'] !!}'>
                                <td>{!! $v['id'] !!}</td>
                                <td>
                                    @if($v['fatherId']==0)
                                        <i class="layui-icon x-show" status='true'>&#xe623;</i>
                                        {!! $v['name'] !!}
                                    @else
                                        &nbsp;&nbsp;&nbsp;&nbsp;
                                        ├{!! $v['name'] !!}
                                    @endif
                                </td>
                                <td>
                                    @if($v['fatherId']==0)
                                        {!! $v['bySort'] !!}
                                    @else
                                        {!! $v['mixBySort'] !!}
                                    @endif
                                </td>
                                <td>
                                    <input type="checkbox" name="switch" lay-text="开启|停用"
                                           {!! $v['isLock']?'':'checked' !!} lay-skin="switch" disabled>
                                </td>
                                <td class="td-manage">
                                    <button class="layui-btn layui-btn layui-btn-xs"
                                            onclick="xadmin.open('编辑','{!! $v['fatherId']==0?'./house/'.$v['id'].'/edit':'./houseRoom/'.$v['id'] !!}')">
                                        <i class="layui-icon">&#xe642;</i>编辑
                                    </button>
                                    @if($v['fatherId']==0)
                                        <button class="layui-btn layui-btn-warm layui-btn-xs"
                                                onclick="xadmin.open('编辑','./house/{!! $v['id'] !!}/')"><i
                                                class="layui-icon">&#xe642;</i>添加子栏目
                                        </button>
                                    @endif

                                    <button class="layui-btn-danger layui-btn layui-btn-xs"
                                            onclick="member_del(this,{!! $v['id'] !!})" href="javascript:;"><i
                                            class="layui-icon">&#xe640;</i>删除
                                    </button>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
{{--                <div class="layui-card-body ">--}}
{{--                    <div class="page">--}}
{{--                        <div>--}}
{{--                            <a class="prev" href="">&lt;&lt;</a>--}}
{{--                            <a class="num" href="">1</a>--}}
{{--                            <span class="current">2</span>--}}
{{--                            <a class="num" href="">3</a>--}}
{{--                            <a class="num" href="">489</a>--}}
{{--                            <a class="next" href="">&gt;&gt;</a></div>--}}
{{--                    </div>--}}
{{--                </div>--}}
            </div>
        </div>
    </div>
</div>
<script>
    layui.use(['form'], function () {
        var form = layui.form;
    });

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            //发异步删除数据
            $.post("/sys/house/" + id, {_method: 'DELETE', _token: '{{csrf_token()}}'},
                function (data) {
                    if (data.success) {
                        $(obj).parents("tr").remove();
                        layer.msg(data.msg, {icon: 1, time: 1000});
                    } else {
                        layer.msg(data.msg, {icon: 4, time: 3000});
                    }
                });
        });
    }

    // 分类展开收起的分类的逻辑
    //
    $(function () {
        $("tbody.x-cate tr[fid!='0']").hide();
        // 栏目多级显示效果
        $('.x-show').click(function () {
            if ($(this).attr('status') == 'true') {
                $(this).html('&#xe625;');
                $(this).attr('status', 'false');
                cateId = $(this).parents('tr').attr('cate-id');
                $("tbody tr[fid=" + cateId + "]").show();
            } else {
                cateIds = [];
                $(this).html('&#xe623;');
                $(this).attr('status', 'true');
                cateId = $(this).parents('tr').attr('cate-id');
                getCateId(cateId);
                for (var i in cateIds) {
                    $("tbody tr[cate-id=" + cateIds[i] + "]").hide().find('.x-show').html('&#xe623;').attr('status', 'true');
                }
            }
        })
    })
    var cateIds = [];

    function getCateId(cateId) {
        $("tbody tr[fid=" + cateId + "]").each(function (index, el) {
            id = $(el).attr('cate-id');
            cateIds.push(id);
            getCateId(id);
        });
    }
</script>
</body>
</html>
