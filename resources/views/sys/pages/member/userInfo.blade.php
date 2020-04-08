<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>基本资料</title>
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <link rel="stylesheet" href="/resource/css/oksub.css">
    <script type="text/javascript" src="/resource/lib/loading/okLoading.js"></script>
</head>
<body class="user-info">
<form class="layui-form changeInfo">
    <div class="user_left">
        <div class="layui-form-item">
            <label class="layui-form-label">姓名</label>
            <div class="layui-input-block">
                <input type="text" name="name" value="{{$name}}" class="layui-input"  lay-verify="required">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">性别</label>
            <div class="layui-input-block">
                <input type="radio" name="sex" value=false title="女" {{$sex==0?'checked':''}}>
                <input type="radio" name="sex" value=true title="男" {{$sex==1?'checked':''}}>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">出生年月</label>
            <div class="layui-input-block">
                <input id="uDate" name="birthDate" type="text" value="{{$birthDate}}" placeholder="请输入出生年月" lay-verify="required"
                       class="layui-input userBirthday">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号码</label>
            <div class="layui-input-block">
                <input type="text" name="mobile" value="{{$mobile}}" placeholder="请输入手机号码" autocomplete="off" class="layui-input"
                       lay-verify="phone">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-block">
                <input type="text" name="mail" value="{{$mail}}" placeholder="请输入邮箱" autocomplete="off" class="layui-input"
                       lay-verify="email">
            </div>
        </div>
{{--        		<div class="layui-form-item userAddress">--}}
{{--        			<label class="layui-form-label">家庭住址</label>--}}
{{--        			<div class="layui-input-inline">--}}
{{--        				<select name="province" lay-filter="province">--}}
{{--        					<option value="">请选择省</option>--}}
{{--        				</select>--}}
{{--        			</div>--}}
{{--        			<div class="layui-input-inline">--}}
{{--        				<select name="city" lay-filter="city" disabled>--}}
{{--        					<option value="">请选择市</option>--}}
{{--        				</select>--}}
{{--        			</div>--}}
{{--        			<div class="layui-input-inline">--}}
{{--        				<select name="area" lay-filter="area" disabled>--}}
{{--        					<option value="">请选择县/区</option>--}}
{{--        				</select>--}}
{{--        			</div>--}}
{{--        		</div>--}}
    </div>
    <div class="layui-form-item" style="margin-left: 5%;">
        <div class="layui-input-block">
            <button class="layui-btn" lay-submit="" lay-filter="changeInfo">立即提交</button>
            <button type="reset" class="layui-btn layui-btn-primary">重置</button>
        </div>
    </div>
</form>
<script type="text/javascript" src="/resource/lib/layui/layui.js"></script>
<script type="text/javascript">
    layui.use(['form', 'layer', 'upload', 'laydate', 'okAddlink'], function () {
        var form = layui.form;
        var upload = layui.upload;
        var $ = layui.jquery;
        var laydate = layui.laydate;
        var $form = $('form');
        //修改资料
        form.on("submit(changeInfo)", function (data) {
            var index = layer.msg('提交中，请稍候', {icon: 16, time: false, shade: 0.8});
            setTimeout(function () {
                $.post("{{url('sys/userInfo')}}", {_token: "{{csrf_token()}}", 'data': data.field},
                    function (data) {
                        if (data.success) {
                            layer.msg(data.msg);
                        } else {
                            layer.msg(data.msg, {icon: 5});
                        }
                    }, "json");
                layer.close(index);
            }, 1000);
            return false; //阻止表单跳转。如果需要表单跳转，去掉这段即可。
        })
        // var okAddlink = layui.okAddlink.init({
        //     province: 'select[name=province]',
        //     city: 'select[name=city]',
        //     area: 'select[name=area]',
        // });
        okLoading.close();
        laydate.render({
            elem: '#uDate', //指定元素
            max: "2020-1-1",
            value: '',
        });
        // okAddlink.render();//渲染三级联动省市区
    });
</script>
</body>
</html>
