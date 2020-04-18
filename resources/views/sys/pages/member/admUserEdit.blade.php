<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    @include('.sys.public.css')
    <script type="text/javascript" src="{{asset('/lib/loading/okLoading.js')}}"></script>
    @include('.sys.public.js')
</head>
<body>
<div class="ok-body">
    <!--form表单-->
    <form class="layui-form ok-form" lay-filter="filter">
        <div class="layui-tab layui-tab-card">
            <ul class="layui-tab-title">
                <li class="layui-this">基础信息</li>
                <li>业务配置</li>
            </ul>
            <div class="layui-tab-content" style="height: 100px;">
                <div class="layui-tab-item layui-show">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><span class="red">*</span>用户名</label>
                        <div class="layui-input-block">
                            <input type="text" name="user_name" placeholder="请输入用户名" autocomplete="off"
                                   class="layui-input"
                                   lay-verify="required" value="" {{$db['id']?'disabled':''}}>
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">姓名</label>
                        <div class="layui-input-block">
                            <input type="text" name="name" placeholder="请输入真实姓名" autocomplete="off" class="layui-input"
                                   value="">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">手机号码</label>
                        <div class="layui-input-block">
                            <input type="text" name="mobile" placeholder="请输入手机号码" autocomplete="off"
                                   class="layui-input"
                                   value="">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">邮箱</label>
                        <div class="layui-input-block">
                            <input type="text" name="email" placeholder="请输入邮箱" autocomplete="off" class="layui-input"
                                   value="">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">密码</label>
                        <div class="layui-input-block">
                            <input type="password" name="pass_word" placeholder="密码为空时不进行修改操作" autocomplete="off"
                                   class="layui-input">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label">出生日期</label>
                        <div class="layui-input-block">
                            <input type="text" name="birth_date" placeholder="请选择出生日期 格式为yyyy-MM-dd" autocomplete="off"
                                   class="layui-input" id="birth_date" value="">
                        </div>
                    </div>
                    <div class="layui-form-item">
                        <label class="layui-form-label"><span class="red">*</span>角色</label>
                        <div class="layui-input-block">
                            <select name="role_id" lay-verify="required">
                                <option value=""></option>
                                @foreach(getRole() as $k=>$v)
                                    <option
                                        value="{{$v['id']}}" {{$v['id']==$role_id?'selected':''}}>{{$v['title'] }}</option>
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
                    {{--        <div class=" layui-form-item">--}}
                    {{--            <label class="layui-form-label">状态</label>--}}
                    {{--            <div class="layui-input-block">--}}
                    {{--                <input type="checkbox" name="isLocks" lay-skin="switch" lay-text="启用|停用"--}}
                    {{--                       {{$db['isLock']==0?'checked':''}} value=o>--}}
                    {{--            </div>--}}
                    {{--        </div>--}}

                    <div class="layui-form-item">
                        <label class="layui-form-label">性别</label>
                        <div class="layui-input-block">
                            <input type="radio" name="sex" value="1"
                                   title="男" {{$db['admUserInfo']['sex']==1?'checked':''}}>
                            <input type="radio" name="sex" value="0"
                                   title="女" {{$db['admUserInfo']['sex']==0?'checked':''}}>
                        </div>
                    </div>
                    {{--                <div class="layui-form-item layui-form-text">--}}
                    {{--                    <label class="layui-form-label">备注</label>--}}
                    {{--                    <div class="layui-input-block">--}}
                    {{--                        <textarea name="remarks" placeholder="请输入内容" class="layui-textarea"></textarea>--}}
                    {{--                    </div>--}}
                    {{--                </div>--}}
                </div>
                <div class="layui-tab-item">
                    <div class="layui-form-item">
                        <label class="layui-form-label"><span class="red">*</span>提成比例</label>
                        <div class="layui-input-block">
                            <input type="text" name="money_ratio" placeholder="请输入提成比例" autocomplete="off"
                                   class="layui-input"
                                   value="" lay-verify="required|number|money_ratio">
                        </div>
                    </div>
                </div>

            </div>
            <div class="layui-form-item">
                <div class="layui-input-block">
                    <button class="layui-btn" lay-submit lay-filter="edit">立即提交</button>
                </div>
            </div>
        {{csrf_field()}}
    </form>
</div>
<!--js逻辑-->
<script type="text/javascript">
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
        laydate.render({elem: "#birth_date", type: "date"});

        form.verify({
            birthdayVerify: [/^((((1[6-9]|[2-9]\d)\d{2})-(0?[13578]|1[02])-(0?[1-9]|[12]\d|3[01]))|(((1[6-9]|[2-9]\d)\d{2})-(0?[13456789]|1[012])-(0?[1-9]|[12]\d|30))|(((1[6-9]|[2-9]\d)\d{2})-0?2-(0?[1-9]|1\d|2[0-8]))|(((1[6-9]|[2-9]\d)(0[48]|[2468][048]|[13579][26])|((16|[2468][048]|[3579][26])00))-0?2-29-))(\s(([01]\d{1})|(2[0123])):([0-5]\d):([0-5]\d))?$/, '日期格式不正确']
            , money_ratio: [
                /(^[1-9]\d$)|(^\d$)|(^100$)/
                , '提成比例为 0-100 之间'
            ]
        });

        form.on("submit(edit)", function (data) {
            okUtils.ajax("/sys/pages/member/admUser/{{$db['id']}}", "{{$db['id']?'put':'post'}}", data.field, true).done(function (response) {
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
