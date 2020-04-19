<!DOCTYPE html>
<html class="x-admin-sm">
<head>
    <meta charset="UTF-8">
    <title>{!! site()['siteWebName'] !!}</title>
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    <meta name="keywords" content="{!! site()['keywords'] !!}">
    <meta name="description" content="{!! site()['description'] !!}">
    <link rel="stylesheet" href="/css/okadmin.css">
    <script type="text/javascript" src="https://cdn.bootcss.com/jquery/3.2.1/jquery.min.js"></script>
    <script src="/lib/layui/layui.js" charset="utf-8"></script>
    <script src="/js/xadmin.js" charset="utf-8"></script>
</head>

<body>
<div class="layui-fluid">
    <div class="layui-row layui-col-space15">
        <div class="layui-col-md12">
            <div class="layui-card">
                <div class="layui-card-body ">
                    <form class="layui-form layui-col-space5" method="post">
                        <div class="layui-input-inline layui-show-xs-block">
                            <input class="layui-input" placeholder="一级菜单路由名称" name="route_title"
                                   lay-verify="required|route_title"></div>
                        <div class="layui-input-inline layui-show-xs-block">
                            <button class="layui-btn" lay-submit="" lay-filter="sreach"><i class="layui-icon"></i>增加路由
                            </button>
                        </div>
                        {{csrf_field()}}
                    </form>
                    <hr>
                </div>
                <div class="layui-card-body ">
                    <table class="layui-table layui-form">
                        <thead>
                        <tr>
                            {{--                            <th width="70">ID</th>--}}
                            <th>路由名称</th>
                            <th>类型</th>
                            <th>排序</th>
                            <th width="250">操作</th>
                        </thead>
                        <tbody class="x-cate">
                        @foreach($data as $v)
                            <tr cate-id='{{$v['id']}}' fid='{{$v['father_id']}}'>
                                {{--                                <td>{{$v['id']}}</td>--}}
                                <td>
                                    @if(isset($v['children']))
                                        <i class="layui-icon x-show" status='true'>&#xe623;</i>
                                    @else
                                        <i class="layui-icon">&#xe63f;</i>
                                    @endif
                                    {!! $v['title'] !!}
                                </td>
                                <td>{!! getRouteType($v['is_type']) !!}</td>
                                <td>{!! $v['by_sort'] !!}</td>
                                <td class="td-manage">
                                    <button class="layui-btn layui-btn layui-btn-xs"
                                            onclick="xadmin.open('编辑','/sys/pages/routes/route/{{$v['id']}}/edit')"><i
                                            class="layui-icon">&#xe642;</i>编辑
                                    </button>
                                    <button class="layui-btn layui-btn-warm layui-btn-xs"
                                            onclick="xadmin.open('添加子路由','/sys/pages/routes/route/{{$v['id']}}')"><i
                                            class="layui-icon">&#xe642;</i>添加子路由
                                    </button>
                                    @if(isset($v['children'])==false)
                                        <button class="layui-btn-danger layui-btn layui-btn-xs"
                                                onclick="del(this,{!! $v['id'] !!})" href="javascript:;"><i
                                                class="layui-icon">&#xe640;</i>删除
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @if(isset($v['children']))
                                @foreach($v['children'] as $li)
                                    {!! children($li,1) !!}
                                @endforeach
                            @endif
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    layui.use(['form', "okLayer", "okLayer", "okUtils"], function () {
        let form = layui.form;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        //自定义验证规则
        form.verify({
            route_title: function (value) {
                if (value.length > 6) {
                    return '路由名称请控制在6个字符以内';
                }

            },
        });

        form.on("submit(sreach)", function (data) {
            okUtils.ajax("{{url('sys/pages/routes/route')}}", "post", data.field, true).done(function (response) {
                okLayer.greenTickMsg(response.msg, function () {
                    location.reload();
                });
            }).fail(function (error) {
                console.log(error)
            });
            return false;
        });
    });

    /*用户-删除*/
    function del(obj, id) {
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        okLayer.confirm("确定要删除吗？", function (index) {
            okUtils.ajax("{{url('sys/pages/routes/route/')}}/" + id, "DELETE", {
                id: id,
                _token: '{{csrf_token()}}'
            }, true).done(function (response) {
                layer.msg(response.msg, {icon: 1, time: 1000});
                $(obj).parents("tr").remove();
            }).fail(function (error) {
                console.log(error)
            });
        })
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
<?php
function children($li, $i)
{
    echo '<tr cate-id=' . $li['id'] . ' fid=' . $li['father_id'] . '>';
//    echo '<td><input type="checkbox" name="" lay-skin="primary"></td>';
//    echo '<td>' . $li['id'] . '</td>';
    echo '<td>';
    for ($k = 1; $k <= $i; $k++) {
        echo '&nbsp;&nbsp;&nbsp;&nbsp;';
    }
    if (isset($li['children'])) {
        echo ' <i class="layui-icon x-show" status="true">&#xe623;</i>' . $li['title'];
    } else {
        echo '|-- ' . $li['title'] . "  (" . $li['href'] . ")";
    }
    echo '</td>';
    echo '<td>' . getRouteType($li['is_type']) . '</td>';
    echo '<td>';
    echo '|';
    for ($k = 1; $k <= $i; $k++) {
        echo '--';
    }
    echo $li['by_sort'];
    echo ' </td>';
    echo '<td class="td-manage">';
    echo '<button class="layui-btn layui-btn layui-btn-xs" onclick="xadmin.open(\'编辑\',\'routeSon/' . $li['id'] . '/edit\')"><i class="layui-icon">&#xe642;</i>编辑</button>';
    echo '<button class="layui-btn layui-btn-warm layui-btn-xs" onclick="xadmin.open(\'添加子路由\',\'/sys/pages/routes/route/' . $li['id'] . '/\')"><i class="layui-icon">&#xe642;</i>添加子路由</button>';
    if (isset($li['children']) == false) {
        echo '<button class="layui-btn-danger layui-btn layui-btn-xs" onclick="del(this,' . $li['id'] . ')" href="javascript:;"><i class="layui-icon">&#xe640;</i>删除</button>';
    }
    echo '</td>';
    echo '</tr>';
    if (isset($li['children'])) {
        $j = $i + 1;
        foreach ($li['children'] as $li2) {
            children($li2, $j);
        }
    }
}
?>
