<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>{!! site()['siteWebName'] !!}</title>
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta name="keywords" content="{!! site()['keywords'] !!}">
    <meta name="description" content="{!! site()['description'] !!}">
    <link rel="stylesheet" href="/resource/css/okadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/resource/lib/layui/layui.js" charset="utf-8"></script>
</head>

<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="路由名称" name="cate_name"></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon"></i>增加路由
                            </button>
                        </div>
                    </form>
                    <hr>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            <th width="20">
                                <input type="checkbox" name="" lay-skin="primary">
                            </th>
                            <th width="70">ID</th>
                            <th>路由名称</th>
                            <th>作用</th>
                            <th width="250">操作</th>
                        </thead>
                        <tbody class="x-cate">
                        @foreach($data as $v)
                            <tr cate-id='{{$v['id']}}' fid='{{$v['fatherId']}}'>
                                <td>
                                    <input type="checkbox" name="" lay-skin="primary">
                                </td>
                                <td>{{$v['id']}}</td>
                                <td>
                                    @if(isset($v['children']))
                                        <i class="layui-icon x-show" status='true'>&#xe623;</i>
                                    @endif
                                    {!! $v['title'] !!}
                                </td>
                                <td>页面</td>
                                <td class="td-manage">
                                    <button class="layui-btn layui-btn layui-btn-xs"
                                            onclick="xadmin .open('编辑','admin-edit.html')"><i
                                            class="layui-icon">&#xe642;</i>编辑
                                    </button>
                                    <button class="layui-btn layui-btn-warm layui-btn-xs"
                                            onclick="xadmin.open('编辑','admin-edit.html')"><i
                                            class="layui-icon">&#xe642;</i>添加子栏目
                                    </button>
                                    <button class="layui-btn-danger layui-btn layui-btn-xs"
                                            onclick="member_del(this,'要删除的id')" href="javascript:;"><i
                                            class="layui-icon">&#xe640;</i>删除
                                    </button>
                                </td>
                            </tr>
                            @if(isset($v['children']))
                                @foreach($v['children'] as $c)
                                    <tr cate-id='{{$c['id']}}' fid='{{$c['fatherId']}}'>
                                        <td>
                                            <input type="checkbox" name="" lay-skin="primary">
                                        </td>
                                        <td>{{$c['id']}}</td>
                                        @if(isset($c['children']))
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                                ├<i class="layui-icon x-show"
                                                    status='true'>&#xe623;</i>{!! $c['title'] !!}
                                            </td>
                                        @else
                                            <td>&nbsp;&nbsp;&nbsp;&nbsp;
                                                ├{!! $c['title'] !!}
                                            </td>
                                        @endif
                                        <td>页面</td>
                                        <td class="td-manage">
                                            <button class="layui-btn layui-btn layui-btn-xs"
                                                    onclick="xadmin .open('编辑','admin-edit.html')"><i
                                                    class="layui-icon">&#xe642;</i>编辑
                                            </button>
                                            <button class="layui-btn layui-btn-warm layui-btn-xs"
                                                    onclick="xadmin.open('编辑','admin-edit.html')"><i
                                                    class="layui-icon">&#xe642;</i>添加子栏目
                                            </button>
                                            <button class="layui-btn-danger layui-btn layui-btn-xs"
                                                    onclick="member_del(this,'要删除的id')" href="javascript:;"><i
                                                    class="layui-icon">&#xe640;</i>删除
                                            </button>
                                        </td>
                                    </tr>
                                    @if(isset($c['children']))
                                        @foreach($c['children'] as $d)
                                            <tr cate-id='{{$d['id']}}' fid='{{$d['fatherId']}}'>
                                                <td>
                                                    <input type="checkbox" name="" lay-skin="primary">
                                                </td>
                                                <td>{{$d['id']}}</td>
                                                <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                    ├{!! $d['title'] !!}
                                                </td>
                                                <td>按钮</td>
                                                <td class="td-manage">
                                                    <button class="layui-btn layui-btn layui-btn-xs"
                                                            onclick="xadmin .open('编辑','admin-edit.html')"><i
                                                            class="layui-icon">&#xe642;</i>编辑
                                                    </button>
                                                    <button class="layui-btn-danger layui-btn layui-btn-xs"
                                                            onclick="member_del(this,'要删除的id')" href="javascript:;"><i
                                                            class="layui-icon">&#xe640;</i>删除
                                                    </button>
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
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
    layui.use(['form', "okLayer"], function () {
        let form = layui.form;
        let okLayer = layui.okLayer;
    });

    /*用户-删除*/
    function member_del(obj, id) {
        layer.confirm('确认要删除吗？', function (index) {
            //发异步删除数据
            $(obj).parents("tr").remove();
            layer.msg('已删除!', {icon: 1, time: 1000});
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
