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
    <div class="row" id="show_1" name="show_1">
        <div class="col-xs-12 col-sm-6 col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <div>
                        <h3 class="panel-title">新型肺炎疫情防控_电子出入卡系统 </h3>
                    </div>
                </div>
                <div class="panel-body">
                    <form class="register-form" id="from_post">
                        <div class="form-group">
                            <span for="mobile">请输入住户联系电话</span>
                            <input type="text" class="form-control" id="mobile" name="mobile" placeholder="请输入联系电话"
                                   value="">
                        </div>
                        <div class="form-group">
                            <span for="expiration">进/离场</span> <br>
                            <label><input type="radio" name="types" value="0" checked='checked'> 进入小区</option></label>&nbsp;&nbsp;
                            <label><input type="radio" name="types" value="1"> 离开小区</option></label>
                        </div>
                        <div class="form-group">
                            <span for="expiration">身体状况</span> <br>
                            <label><input type="radio" name="status" value="0" checked='checked'> 体温正常</option></label>&nbsp;&nbsp;
                            <label><input type="radio" name="status" value="1"> 体温异常</option></label>
                        </div>
                        <br>
                        <div class="form-group" style="margin-bottom: 0px;text-align-last: center;">
                            <button id="Btn" type="submit" class="btn btn-default" style="width: 220px;">确认</button>
                        </div>
                    </form>
                    <e style="color: #888686;font-size: 13px;">请输入管理员密码后展示录入码：</e>
                    <input type="password" class="form-control" id="pwd" name="pwd" placeholder="请在输入管理员密码后自动展示录入码"
                           value="">
                    <div id="show_3" name="show_3" class="register-form" style="text-align-last: center;">
                        <a href="/csign/create"><img
                                src="http://qr.liantu.com/api.php?w=140&m=10&text={!! site()['doMain'] !!}/csign/create"/></a>
                        <div style="color: #888686;font-size: 13px;text-align-last: center;">点击或扫描二维码进行新住户录入</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="show_2" name="show_2">
        <div class="col-xs-12 col-sm-6 col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">新型肺炎疫情防控_电子出入卡系统 </h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <span for="street">街道名称 </span>
                        <input type="text" class="form-control" id="street" name="street" value="" readonly>
                    </div>
                    <div class="form-group">
                        <span for="community">社区名称 </span>
                        <input type="text" class="form-control" id="community" name="community" value="" readonly>
                    </div>
                    <div class="form-group">
                        <span for="homeName">家庭住址 </span>
                        <input type="text" class="form-control" id="homeName" name="homeName" value="" readonly>
                    </div>
                    <div class="form-group">
                        <span for="users">住户信息</span><br>
                        <span for="users" style="border: 1px dashed #9e9999;" id="users">
                    </div>
                    <div class="form-group" style="margin-bottom: 0px;text-align-last: center;">
                        <a class="btn btn-default" href="{{url('csign')}}" style="width: 220px;">确认</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div align="center"><a href="http://www.miitbeian.gov.cn/" target="_blank">{!! site()['siteICP'] !!}</a></div>
<script type="text/javascript">
    $(document).ready(function () {
        $("#show_2").hide();
        $("#show_3").hide();
        $('#pwd').change(function () {
            if ($("#pwd").val() == '888666') {
                $("#show_3").show();
            }
        });
    })
    //表单验证
    $("#from_post").validate({
        rules: {
            mobile: {
                required: true,
                maxlength: 11,
                minlength: 11,
                digits: true
            }

        },
        errorElement: "a",
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        messages: {
            mobile: {
                required: '请填写联系电话',
                maxlength: "请输入正确的手机号",
                minlength: "请输入正确的手机号",
                digits: "请输入正确的手机号"
            }
        },
        submitHandler: function (form) {
            $('#Btn').addClass('btn-dis').html('验证中...').attr('disabled', true);
            $.post("{{url('csign')}}/" + $("#mobile").val(),
                {
                    _method: "PATCH",
                    _token: '{{csrf_token()}}',
                    data: {
                        'mobile': $("#mobile").val(),
                        'types': $('input:radio[name="types"]:checked').val(),
                        'status': $('input:radio[name="status"]:checked').val()
                    }
                },
                function (data) {
                    if (data.success == true) {
                        //alert(data.minUrl);
                        $(document).ready(function () {
                            $("#show_1").hide();
                            $("#show_2").show();
                            $("#street").val(data.street);
                            $("#community").val(data.community);
                            $("#homeName").val(data.homeName);
                            $("#users").text(data.users);
                        })
                        //window.location.href = '../?api-getUrl.html';
                    } else {
                        layer.msg(data.msg, {shift: 6});
                        $('#Btn').removeClass('btn-dis').html('确认').attr('disabled', false);
                    }

                },);
            return false;
        }
    });
</script>
