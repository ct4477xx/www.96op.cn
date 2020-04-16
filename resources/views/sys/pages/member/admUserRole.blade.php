<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>角色列表</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    <script type="text/javascript" src="/resource/lib/loading/okLoading.js"></script>
    @include('.sys.public.js')
</head>
<body>
<div class="ok-body">
    <!--模糊搜索区域-->
{{--    <div class="layui-row">--}}
{{--        <form class="layui-form layui-col-md12 ok-search">--}}
{{--            <input class="layui-input" placeholder="开始日期" autocomplete="off" id="startTime" name="startTime">--}}
{{--            <input class="layui-input" placeholder="截止日期" autocomplete="off" id="endTime" name="endTime">--}}
{{--            <input class="layui-input" placeholder="请输入角色名" autocomplete="off" name="name">--}}
{{--            <div class="layui-inline">--}}
{{--                <label class="layui-form-label">请选择状态</label>--}}
{{--                <div class="layui-input-inline">--}}
{{--                    <select name="status" lay-verify="">--}}
{{--                        <option value="" selected>请选择状态</option>--}}
{{--                        <option value="o">已启用</option>--}}
{{--                        <option value="n">已停用</option>--}}
{{--                    </select>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--            <button class="layui-btn" lay-submit="" lay-filter="search">--}}
{{--                <i class="layui-icon layui-icon-search"></i>--}}
{{--            </button>--}}
{{--        </form>--}}
{{--    </div>--}}
<!--数据表格-->
    <table class="layui-hide" id="tableId" lay-filter="tableFilter"></table>
</div>
<!--js逻辑-->
<script>
    layui.use(["element", "table", "form", "laydate", "okLayer", "okUtils"], function () {
        let table = layui.table;
        let form = layui.form;
        let laydate = layui.laydate;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        okLoading.close();
        laydate.render({elem: "#startTime", type: "datetime"});
        laydate.render({elem: "#endTime", type: "datetime"});

        let roleTable = table.render({
            elem: "#tableId",
            url: '/sys/pages/member/admUserRoleRead',
            limit: '{!! pages()['limit'] !!}',
            limits: [{!! pages()['limits'] !!}],
            page: true,
            toolbar: '<div class="layui-btn-container">\n' +
                '        <button class="layui-btn layui-btn-sm" lay-event="add">添加角色</button>\n' +
                '        <button class="layui-btn layui-btn-sm layui-btn-normal" lay-event="batchEnabled">批量启用</button>\n' +
                '        <button class="layui-btn layui-btn-sm layui-btn-warm" lay-event="batchDisabled">批量停用</button>\n' +
                '        <button class="layui-btn layui-btn-sm layui-btn-danger" lay-event="batchDel">批量删除</button>\n' +
                '    </div>',
            size: "sm",
            cols: [[
                {type: "checkbox"},
                {field: "code", title: "编号", width: 170, sort: true},
                {field: "name", title: "角色名", width: 100},
                {field: "remarks", title: "备注", width: 180},
                {field: "isLock", title: "状态", width: 85},
                {field: "addName", title: "创建者", width: 90},
                {field: "addTime", title: "创建时间", width: 145},
                {field: "upName", title: "最后修改人", width: 90},
                {field: "upTime", title: "修改时间", width: 145},
                {
                    title: "操作", width: 100, align: "center", fixed: "right", templet: function (d) {
                        return "<a href=\"javascript:\" title=\"编辑\" lay-event=\"edit\"><i class=\"layui-icon\">&#xe642;</i></a>\n" +
                            "<a href=\"javascript:\" title=\"删除\" lay-event=\"del\"><i class=\"layui-icon\">&#xe640;</i></a>"
                    }
                }
            ]],
            done: function (res, curr, count) {
                // console.log(res, curr, count);
            }
        });

        form.on("submit(search)", function (data) {
            roleTable.reload({
                where: data.field,
                page: {curr: 1}
            });
            // console.log("0000")
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
                    edit(data.id);
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
                    okUtils.ajax("admUserRoleStart", "post", {
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
                    okUtils.ajax("admUserRoleStop", "post", {
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
            okLayer.open("添加角色", "admUserRole/create", "90%", "90%", null, function () {
                roleTable.reload();
            })
        }


        function batchDel() {
            okLayer.confirm("确定要批量删除吗？", function (index) {
                layer.close(index);
                let idsStr = okUtils.tableBatchCheck(table);
                if (idsStr) {
                    okUtils.ajax("admUserRoleDel", "post", {
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

        function edit(id) {
            okLayer.open("编辑角色", "admUserRole/" + id + "/edit", "90%", "90%", null, function () {
                roleTable.reload();
            })
        }

        function del(id) {
            okLayer.confirm("确定要删除吗？", function () {
                okUtils.ajax("admUserRoleDel", "post", {
                    id: id,
                    _token: '{{csrf_token()}}'
                }, true).done(function (response) {
                    console.log(response);
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
