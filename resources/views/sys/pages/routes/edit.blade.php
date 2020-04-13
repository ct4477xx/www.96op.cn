<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
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
            <label class="layui-form-label">路由名称</label>
            <div class="layui-input-block">
                <input type="text" name="title" placeholder="请输入路由名称" autocomplete="off" class="layui-input"
                       lay-verify="required" value="{{$title}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">路由地址</label>
            <div class="layui-input-block">
                <input type="text" name="href" placeholder="请输入路由地址" autocomplete="off" class="layui-input"
                       lay-verify="required" value="{{$href ?? "/"}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">图标资源</label>
            <div class="layui-input-block">
                <select name="fontFamily" lay-verify="required">
                    <option value=""></option>
                    <option value="ok-icon" {{$fontFamily=='ok-icon'?'selected':''}}>ok-icon</option>
                    <option value="layui-icon" {{$fontFamily=='layui-icon'?'selected':''}}>layui-icon</option>
                </select>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">ICON</label>
            <div class="layui-input-block">
                <input type="text" name="icon" placeholder="请输入icon" autocomplete="off" class="layui-input"
                       lay-verify="required" value="{{$icon ?? '&#xe602;'}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label for="phone" class="layui-form-label">
                <span class="x-red">*</span>排序
            </label>
            <div class="layui-input-inline">
                <input type="text" id="bySort" name="bySort" required="" value="{!! $bySort !!}"
                       lay-verify="required|bySort"
                       autocomplete="off" class="layui-input">
            </div>
            <div class="layui-form-mid layui-word-aux">
                <span class="x-red">*</span>数字越大排序越靠前, 最大数值为1000
            </div>
        </div>
        <div class="layui-form-item">
            <div class="layui-input-block">
                <button class="layui-btn" lay-submit lay-filter="edit">立即提交</button>
                <button type="reset" class="layui-btn layui-btn-primary">重置</button>
            </div>
        </div>
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script>
    layui.use(["form", "okUtils", "okLayer"], function () {
        let form = layui.form;
        let okUtils = layui.okUtils;
        let okLayer = layui.okLayer;
        okLoading.close();

        //自定义验证规则
        form.verify({
            bySort: function (value) {
                if (value > 1000) {
                    return '排序最大值请控制在1000以内';
                }
                if (value < 0) {
                    return '排序最小值请为0';
                }
            },
        });

        form.on("submit(edit)", function (data) {
            okUtils.ajax("/sys/pages/routes/route/{{$id}}", "PATCH", data.field, true).done(function (response) {
                okLayer.greenTickMsg(response.msg, function () {
                    parent.layer.close(parent.layer.getFrameIndex(window.name));
                });
            }).fail(function (error) {
                console.log(error)
            });
            return false;
        });
    });
</script>
</body>
</html>
