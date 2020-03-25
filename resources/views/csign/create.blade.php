<html lang="zh-CN">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>小区出入签到({!! site()['siteWebName'] !!})</title>
    @include('pubilc/csign/css')
    @include('pubilc/csign/js')
</head>
<body role="document" style="">
<div class="container">
    <div class="row">
        <div class="col-xs-12 col-sm-6 col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div>
                        <h3 class="panel-title">新型肺炎疫情防控_电子出入卡系统_住户添加 </h3>
                    </div>
                </div>
                <div class="panel-body">
                    <form class="register-form" id="from_post">
                        <div class="form-group">
                            <span for="street">街道名称 </span><br>
                            <select class="form-control" id="street" name="street">
                                <option value="">请选择街道</option>
                                @foreach(signStreet() as $v)
                                    <option value="{{$v['Id']}}">{{$v['name']}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <span for="community">社区名称 </span>
                            <select class="form-control" id="community" name="community">
                                <option value="">请选择社区</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <span for="homeName">家庭住址 </span>
                            <input type="text" class="form-control" id="homeName" name="homeName"
                                   placeholder="请输入小区名称与家庭住址" value="">
                        </div>
                        <div class="form-group">
                            <span for="users">住户信息 </span>
                            <input type="text" class="form-control" id="users" name="users"
                                   placeholder="请输入常住人员姓名，支持多个姓名" value="">
                        </div>
                        <div class="form-group">
                            <span for="mobile">联系电话 </span>
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="请输入联系电话"
                                   value="">
                        </div>
                        <br>
                        <div class="form-group" style="margin-bottom: 0px;text-align-last: center;">
                            <button id="Btn" type="submit" class="btn btn-default"
                                    style="width: 220px;background-color: #c8ecf3;">确认
                            </button>
                            <br><br>
                            <input type="button" value="返回"
                                   onclick="javascrtpt:window.location.href='{!! site()['doMain'] !!}/csign'"
                                   class="btn btn-default" style="width: 220px;"/>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div align="center"><a href="http://www.beian.miit.gov.cn/" target="_blank">{!! site()['siteICP'] !!}</a></div>
    <script type="text/javascript">
        $(function () {
            //社区选择
            $("#street").change(function () {
                $.post("{{url('/AjaxReadKey/community')}}/" + $(this).children(":selected").val(),
                    {
                        _method: "GET",
                        _token: '{{csrf_token()}}'
                    },
                    function (data) {
                        $("#community").html(data);
                    });
            })

        })
    </script>
    <script type="text/javascript">
        $(document).ready(function () {
            $("#show_2").hide();
        })
        //表单验证
        $("#from_post").validate({
            rules: {
                numbers: {
                    required: true,
                    rangelength: [2, 25]
                },
                street: "required",
                community: "required",
                homeName: {
                    required: true,
                    rangelength: [2, 45]
                },
                users: {
                    required: true,
                    rangelength: [2, 30]
                },
                mobile: {
                    required: true,
                    maxlength: 11,
                    minlength: 11,
                    digits: true
                },
            },
            errorElement: "a",
            errorPlacement: function (error, element) {
                $(element).parents('.form-group').append(error);
            },
            messages: {
                numbers: {
                    required: '请填写住宅编号',
                    rangelength: "住宅编号不得少于2位"
                },
                street: {
                    required: '请选择街道名称'
                },
                community: {
                    required: '请选择社区名称'
                },
                homeName: {
                    required: '请填写家庭住址',
                    rangelength: "家庭地址请控制在2-45个字符以内"
                },
                users: {
                    required: '请填写住户信息',
                    rangelength: "家庭成员名称可以只写一位或多位"
                },
                mobile: {
                    required: '请填写联系电话',
                    maxlength: "请输入正确的手机号",
                    minlength: "请输入正确的手机号",
                    digits: "请输入正确的手机号"
                }
            },
            submitHandler: function (form) {
                $('#Btn').addClass('btn-dis').html('验证中...').attr('disabled', true);
                $.post("{{url('csign')}}",
                    {
                        _method: "POST",
                        _token: '{{csrf_token()}}',
                        data: {
                            'street': $("#street").val(),
                            'community': $("#community").val(),
                            'homeName': $("#homeName").val(),
                            'users': $("#users").val(),
                            'mobile': $("#mobile").val()
                        }
                    },
                    function (data) {
                        if (data.success) {
                            window.location.href = '{{site()['doMain']}}/csign';
                        } else {
                            layer.msg(data.msg, {shift: 6});
                            $('#Btn').removeClass('btn-dis').html('缩短网址').attr('disabled', false);
                        }
                        timeout: 3000
                    },);
                return false;


            }
        });
    </script>
