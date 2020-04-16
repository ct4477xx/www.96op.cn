<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    <script type="text/javascript" src="/resource/lib/loading/okLoading.js"></script>
    @include('.sys.public.js')
</head>
<body>
<div class="ok-body">
    <!--form表单-->
    <form class="layui-form layui-form-pane ok-form">
        <div class="layui-form-item">
            <label class="layui-form-label">角色名</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="请输入角色名" autocomplete="off" class="layui-input"
                       lay-verify="required" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">备注</label>
            <div class="layui-input-block">
                <input type="text" name="remarks" placeholder="请输入备注" autocomplete="off" class="layui-input"
                       lay-verify="" value="">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">权限</label>
            <div class="layui-input-block">
                <div id="permissionTree"></div>
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="add">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script type="text/javascript">
    layui.use(["element", "form", "tree", "okLayer", "okUtils"], function () {
        let form = layui.form;
        let tree = layui.tree;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;

        let data = [{!! $data !!}]
        tree.render({
            elem: "#permissionTree",
            data: data,
            id: 'permissionTreeId',
            showCheckbox: true,
            showLine: true //是否开启连接线
        });
        tree.setChecked('permissionTreeId', {!! $role !!});
        okLoading.close();

        form.on("submit(add)", function (data) {
            // TODO 权限节点校验
            // 请求后台
            okUtils.ajax("/sys/pages/member/admUserRole", "post", data.field, true).done(function (response) {
                console.log(response);
                okLayer.greenTickMsg(response.msg, function () {
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                });
            }).fail(function (error) {
                console.log(error)
            });
            return false;
        });
    })
</script>
</body>
</html>
