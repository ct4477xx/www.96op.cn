<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    <script type="text/javascript" src="{{asset('/resource/lib/loading/okLoading.js')}}"></script>
    @include('.sys.public.js')
</head>
<body>
<div class="ok-body">
    <!--form表单-->
    <form class="layui-form ok-form" lay-filter="filter">
        <div class="layui-form-item">
            <label class="layui-form-label">用户名</label>
            <div class="layui-input-block">
                <input type="text" placeholder="{{$db['userName']}}" autocomplete="off" class="layui-input"
                       value="{{$db['userName']}}" disabled>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">姓名</label>
            <div class="layui-input-block">
                <input type="text" name="name" placeholder="请输入真实姓名" autocomplete="off" class="layui-input"
                       value="{{$db['adm_user_info']['name']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">手机号码</label>
            <div class="layui-input-block">
                <input type="text" name="mobile" placeholder="请输入手机号码" autocomplete="off" class="layui-input"
                       value="{{$db['adm_user_info']['mobile']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">邮箱</label>
            <div class="layui-input-block">
                <input type="text" name="mail" placeholder="请输入邮箱" autocomplete="off" class="layui-input"
                       value="{{$db['adm_user_info']['mail']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">密码</label>
            <div class="layui-input-block">
                <input type="password" name="password" placeholder="密码为空时不进行修改操作" autocomplete="off"
                       class="layui-input">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">出生日期</label>
            <div class="layui-input-block">
                <input type="text" name="birthDate" placeholder="请选择出生日期 格式为yyyy-MM-dd" autocomplete="off"
                       class="layui-input" id="birthDate" value="{{$db['adm_user_info']['birthDate']}}">
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">角色</label>
            <div class="layui-input-block">
                <select name="role" lay-verify="required">
                    @foreach(getRole() as $k=>$v)
                        <option
                            value="{!! $v['id'] !!}" {{$v['id']== $role?'selected="selected"':''}}>{!! $v['name'] !!}</option>
                    @endforeach
                </select>
            </div>
        </div>
        {{--		<div class="layui-form-item">--}}
        {{--			<label class="layui-form-label">兴趣</label>--}}
        {{--			<div class="layui-input-block">--}}
        {{--				<input type="checkbox" name="like[write]" value="1" title="写作">--}}
        {{--				<input type="checkbox" name="like[read]" value="2" title="阅读">--}}
        {{--				<input type="checkbox" name="like[run]" value="3" title="运动">--}}
        {{--			</div>--}}
        {{--		</div>--}}
        <div class="layui-form-item">
            <label class="layui-form-label">状态</label>
            <div class="layui-input-block">
                <input type="checkbox" name="isLock" lay-skin="switch" lay-text="启用|停用"
                       {{$db['isLock']==0?'checked':''}} value=o>
            </div>
        </div>
        <div class="layui-form-item">
            <label class="layui-form-label">性别</label>
            <div class="layui-input-block">
                <input type="radio" name="sex" value="1" title="男" {{$db['adm_user_info']['sex']==1?'checked':''}}>
                <input type="radio" name="sex" value="0" title="女" {{$db['adm_user_info']['sex']==0?'checked':''}}>
            </div>
        </div>
        {{--        <div class="layui-form-item layui-form-text">--}}
        {{--            <label class="layui-form-label">备注</label>--}}
        {{--            <div class="layui-input-block">--}}
        {{--                <textarea name="remarks" placeholder="请输入内容" class="layui-textarea"></textarea>--}}
        {{--            </div>--}}
        {{--        </div>--}}
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
    let initData;

    function initForm(data) {
        let jsonString = JSON.stringify(data);
        initData = JSON.parse(jsonString);
    }

    layui.use(["element", "form", "laydate", "okLayer", "okUtils"], function () {
        let form = layui.form;
        let laydate = layui.laydate;
        let okLayer = layui.okLayer;
        let okUtils = layui.okUtils;
        okLoading.close();
        form.val("filter", initData);

        laydate.render({elem: "#birthDate", type: "date"});

        form.verify({
            birthdayVerify: [/^((((1[6-9]|[2-9]\d)\d{2})-(0?[13578]|1[02])-(0?[1-9]|[12]\d|3[01]))|(((1[6-9]|[2-9]\d)\d{2})-(0?[13456789]|1[012])-(0?[1-9]|[12]\d|30))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-))(\s(([01]\d{1})|(2[0123])):([0-5]\d):([0-5]\d))?$/, '日期格式不正确']
        });

        form.on("submit(edit)", function (data) {
            okUtils.ajax("/sys/pages/member/admUser/{{$db['id']}}", "put", data.field, true).done(function (response) {
                // console.log(response);
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
