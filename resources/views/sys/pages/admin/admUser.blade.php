<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    <script type="text/javascript" src="{{asset('/lib/loading/okLoading.js')}}"></script>
    @include('.sys.public.js')
</head>
<body>
<div class="ok-body">
    <!--模糊搜索区域-->
    <div class="layui-row">
        <form class="layui-form ok-search-form">
            <div class="layui-form-item">
                <div class="layui-inline">
                    <label class="layui-form-label">开始日期</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" placeholder="开始日期" autocomplete="off" id="start_time"
                               name="start_time">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">截止日期</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" placeholder="截止日期" autocomplete="off" id="end_time"
                               name="end_time">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">用户名</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" placeholder="用户名" autocomplete="off" name="user_name">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">姓名</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" placeholder="姓名" autocomplete="off" name="name">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">手机号码</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" placeholder="手机号码" autocomplete="off" name="mobile">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">邮箱</label>
                    <div class="layui-input-inline">
                        <input type="text" class="layui-input" placeholder="邮箱" autocomplete="off" name="email">
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">请选择角色</label>
                    <div class="layui-input-inline">
                        <select name="role_id" lay-verify="">
                            <option value="" selected>请选择角色</option>
                            @foreach(getUserRole(0) as $k=>$v)
                                <option value="{!! $v['id'] !!}">{!! $v['title'] !!}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <label class="layui-form-label">请选择状态</label>
                    <div class="layui-input-inline">
                        <select name="is_lock" lay-verify="">
                            <option value="" selected>请选择状态</option>
                            <option value="o">已启用</option>
                            <option value="n">已停用</option>
                        </select>
                    </div>
                </div>
                <div class="layui-inline">
                    <div class="layui-input-inline">
                        <button class="layui-btn" lay-submit="" lay-filter="search">
                            <i class="layui-icon">&#xe615;</i>
                        </button>
                    </div>
                </div>
            </div>
        </form>
    </div>
    <!--数据表格-->
    <table class="layui-hide" id="tableId" lay-filter="tableFilter"></table>
</div>
<!--js逻辑-->
<script>
    var json;
    layui.use(["element", "jquery", "table", "form", "laydate", "okLayer", "okUtils", "okLayx"], function () {
        let table = layui.table;
        let form = layui.form;
        let laydate = layui.laydate;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        let okLayx = layui.okLayx;
        let $ = layui.jquery;
        laydate.render({elem: "#start_time", type: "datetime"});
        laydate.render({elem: "#end_time", type: "datetime"});
        okLoading.close($);
        okLayx.notice({
            title: "温馨提示",
            type: "warning",
            message: "{!! frame()['message'] !!}"
        });
        let userTable = table.render({
            elem: '#tableId',
            url: '/sys/pages/admin/admUserRead',
            limit: '{!! frame()['limit'] !!}',
            limits: [{!! frame()['limits'] !!}],
            title: '用户列表_{{getTime(3)}}',
            page: true,
            toolbar: '<div class="layui-btn-container">\n' +
                @if(hasPower(53)) '<button class="layui-btn layui-btn-sm" lay-event="add">添加用户</button>\n' + @endif
                    @if(hasPower(75)) '<button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="batchEnabled">批量启用</button>\n' +
                '<button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="batchDisabled">批量停用</button>\n' + @endif
                    @if(hasPower(55)) '<button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="batchDel">批量删除</button>\n' + @endif
                    '    </div>',
            size: "sm",
            cols:
                [[
                    {type: "checkbox", fixed: "left"},
                    {field: "code", title: "编号", width: 100},
                    {field: "user_name", title: "用户名", width: 120},
                    {field: "name", title: "姓名", width: 100},
                    {field: "birth_date", title: "出生日期", width: 100, sort: true},
                    {field: "sex", title: "性别", width: 60},
                    {field: "email", title: "邮箱", width: 150},
                    {field: "mobile", title: "手机号码", width: 120},
                    {field: "role_name", title: "角色", width: 100},
                    {field: "is_lock_name", title: "状态", width: 85, sort: true},
                    {field: "money_ratio", title: "提成比例", width: 95, sort: true},
                    {field: "add_name", title: "创建者", width: 90},
                    {field: "add_time", title: "创建时间", width: 145, sort: true},
                    {field: "up_name", title: "最后修改人", width: 90},
                    {field: "up_time", title: "修改时间", width: 145, sort: true},
                    {
                        title: "操作", width: 100, align: "center", fixed: "right", templet: function (d) {
                            var edit = "@if(hasPower(54))<a href=\"javascript:\" title=\"编辑\" lay-event=\"edit\"><i class=\"layui-icon\">&#xe642;</i></a>@endif";
                            var del = "@if(hasPower(55))<a href=\"javascript:\" title=\"删除\" lay-event=\"del\"><i class=\"layui-icon\">&#xe640;</i></a>@endif";
                            if (d.is_lock == 1) {
                                return edit + del;
                            } else {
                                return edit;
                            }
                        }
                    }
                ]],
            done: function (res, curr, count) {
                //console.info(res, curr, count);
            }
        });
        form.on("submit(search)", function (data) {
            userTable.reload({
                where: data.field,
                page: {curr: 1}
            });
            return false;
        });

        table.on("toolbar(tableFilter)", function (obj) {
            switch (obj.event) {
                case "batchEnabled":
                    batchEnabled();
                    break;
                case "batchDisabled":
                    batchDisabled();
                    break;
                case "add":
                    add();
                    break;
                case "batchDel":
                    batchDel();
                    break;
            }
        });

        table.on("tool(tableFilter)", function (obj) {
            let data = obj.data;
            switch (obj.event) {
                case "edit":
                    edit(data);
                    break;
                case "del":
                    del(data.id);
                    break;
            }
        });

        function batchEnabled() {
            okLayer.confirm("确定要批量启用吗？", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("admUserStart", "post", {
                        id: idsStr,
                        _token: '{{csrf_token()}}'
                    }, true).done(function (response) {
                        okUtils.tableSuccessMsg(response.msg);
                    }).fail(function (error) {
                        console.log(error)
                    });
                }
            });
        }

        function batchDisabled() {
            okLayer.confirm("确定要批量停用吗？", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("admUserStop", "post", {
                        id: idsStr,
                        _token: '{{csrf_token()}}'
                    }, true).done(function (response) {
                        okUtils.tableSuccessMsg(response.msg);
                    }).fail(function (error) {
                        console.log(error)
                    });
                }
            });
        }

        function batchDel() {
            var checkStatus = table.checkStatus('tableId');
            for (var i = 0; i < checkStatus['data'].length; i++) {
                if (checkStatus['data'][i]['is_lock'] == 0) {
                    layer.msg("只有在停用状态下才可以被删除哦", {icon: 5});
                    return;
                }
            }
            okLayer.confirm("确定要批量删除吗？", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("admUserDel", "post", {
                        id: idsStr,
                        _token: '{{csrf_token()}}'
                    }, true).done(function (response) {
                        okUtils.tableSuccessMsg(response.msg);
                    }).fail(function (error) {
                        console.log(error)
                    });
                }
            });
        }


        function add() {
            json = JSON.stringify('');
            okLayer.open("添加用户", "admUser/create", "90%", "90%", null, function () {
                userTable.reload();
            })
        }

        function edit(data) {
            json = JSON.stringify(data);
            okLayer.open("编辑用户", "admUser/" + data.id + "/edit", "90%", "90%", null, function () {
                userTable.reload();
            })
        }


        function del(id) {
            okLayer.confirm("确定要删除吗？", function () {
                okUtils.ajax("admUserDel", "post", {
                    id: id,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    okUtils.tableSuccessMsg(response.msg);
                }).fail(function (error) {
                    console.log(error)
                });
            })
        }
    })
</script>
</body>
</html>
