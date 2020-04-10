<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>修改密码</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    <script type="text/javascript" src="{{asset('/resource/lib/loading/okLoading.js')}}"></script>
    @include('.sys.public.js')
</head>
<body class="childrenBody seting-pass">
<form class="layui-form changePwd">
    <div class="layui-form-item">
        <label class="layui-form-label">用户姓名</label>
        <div class="layui-input-block">
            <input type="text" value="{{Cookie::get('admName')}}" disabled class="layui-input layui-disabled">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">旧密码</label>
        <div class="layui-input-block">
            <input name="oldPwd" type="password" value="" placeholder="请输入旧密码" lay-verify="required|oldPwd"
                   class="layui-input pwd">
        </div>
    </div>
    <div class="layui-form-item">
        <label class="layui-form-label">新密码</label>
        <div class="layui-input-block">
            <input name="password" type="password" value="" placeholder="请输入新密码" lay-verify="required|pass" id="oldPwd"
                   class="layui-input pwd">
        </div>
    </div>
    {{--    <div class="layui-form-item">--}}
    {{--        <label class="layui-form-label">确认密码</label>--}}
    {{--        <div class="layui-input-block">--}}
    {{--            <input type="password" value="" placeholder="请确认密码" lay-verify="required|confirmPwd"--}}
    {{--                   class="layui-input pwd">--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="layui-form-item">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="changePwd">立即修改</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
<script type="text/javascript">
    layui.use(['form', 'layer'], function () {
        var form = layui.form,
            layer = layui.layer,
            $ = layui.jquery,
            $form = $('form');
        okLoading.close();
        //添加验证规则verify
        form.verify({
            pass: [
                /^[\S]{2,16}$/
                , '密码必须2到16位，且不能出现空格'
            ],
            // oldPwd: function (value, item) {
            //    if(value!=''){
            //        return "旧密码错误，请重新输入！";
            //     }
            // },
            // confirmPwd: function (value, item) {
            //     if (!new RegExp($("#oldPwd").val()).test(value)) {
            //         return "两次输入密码不一致，请重新输入！";
            //     }
            // },
        });
        //修改密码
        form.on("submit(changePwd)", function (data) {
            var index = layer.msg('提交中，请稍候', {icon: 16, time: false, shade: 0.8});
            setTimeout(function () {
                $.post("{{url('sys/userPwd')}}", {_token: "{{csrf_token()}}", 'data': data.field},
                    function (data) {
                        if (data.success) {
                            layer.msg(data.msg);
                            $(".pwd").val('');
                        } else {
                            layer.msg(data.msg, {icon: 5});
                        }
                    }, "json");
                layer.close(index);
            }, 1000);
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        })
    });
</script>
</body>
</html>
