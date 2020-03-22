<html lang="zh-CN">
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>短网址生成({!! $site_WebName !!})</title>
    @include('pubilc/url/css')
    @include('pubilc/url/js')
</head>
<body role="document" style="">
<header class="navbar navbar-default navbar-fixed-top">
    <div class="container">
        <div class="navbar-header">
            <a class="navbar-brand"><strong>{!! $doMain !!} 短网址生成工具</strong></a>
        </div>
    </div>
</header>
<div class="container">
    <div class="row" id="show_1" name="show_1">
        <div class="col-xs-12 col-sm-6 col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">短网址/短链接(短码)</h3>
                </div>
                <div class="panel-body">
                    <form class="register-form" id="from_post">
                        <div class="form-group">
                            <span for="url">请输入长网址</span>
                            <input type="text" class="form-control" id="url" name="url"
                                   placeholder="{!! $doMain !!}/index.html" value="">
                        </div>
                        <div class="form-group">
                            <span for="expiration">过期时间（可不填）</span>
                            <input type="text" class="form-control" id="expiration" name="expiration"
                                   placeholder="仅数字，1 至 365 天(默认30天)" value="">
                        </div>
                        <div class="form-group">
                            <span for="infoBak">说明（可不填）</span>
                            <input type="text" class="form-control" id="infoBak" name="infoBak" placeholder="没有可不填写"
                                   value="">
                        </div>
                        <div class="form-group" style="margin-bottom: 0px">
                            <button id="Btn" type="submit" class="btn btn-default">缩短网址</button>
                        </div>
                    </form>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <span for="url">请输入短码</span>
                        <input name="qs_minUrl" id="qs_minUrl" type="text" class="form-control-1"
                               placeholder="请输入短码(区分大小写)">&nbsp;<a href="" id="qs_ksfw">快速访问</a>
                        &nbsp;<e style="color: #888686;font-size: 13px;">(例如： "{!! $doMain !!}/t/96op", "96op"即为短码)</e>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row" id="show_2" name="show_2">
        <div class="col-xs-12 col-sm-6 col-md-8">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title">短网址创建成功！</h3>
                </div>
                <div class="panel-body">
                    <div class="form-group">
                        <span for="expiration" id="oldurl">原网址: </span>
                    </div>
                    <div class="form-group">
                        <span for="expiration">短域名: </span>
                        <input name="minUrl" id="minUrl" type="text" onClick="myCopy()" class="form-control-1" value=""
                               readonly>&nbsp;<a target="_blank" href="" id="ksfw">快速访问</a>
                    </div>
                    <div class="form-group">
                        <span for="expiration">二维码: </span>
                        <img id="rwm" src=""/>
                    </div>
                    <div class="form-group">
                        <span for="expiration" id="expiration_">过期时间:</span>
                    </div>
                    <div class="form-group" style="margin-bottom: 0px">
                        <a class="btn btn-default" href="{{url('t')}}">继续生成</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div align="center"><a href="http://www.miitbeian.gov.cn/" target="_blank">粤ICP备18113405号</a></div>
<script type="text/javascript">
    function myCopy() {
        var ele = document.getElementById("minUrl");
        ele.select();
        document.execCommand("Copy");
        alert("复制成功");
    }
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $("#show_2").hide();
        $('#qs_minUrl').on('input propertychange', function () {
            var qs_minUrl = $(this).val();
            $("#qs_ksfw").attr("href", "{!! $doMain !!}/t/" + qs_minUrl);
        });
    })
    //表单验证
    $("#from_post").validate({
        rules: {
            url: 'required'
        },
        errorElement: "a",
        errorPlacement: function (error, element) {
            $(element).parents('.form-group').append(error);
        },
        messages: {
            url: {
                required: '请填写长域名'
            }
        },
        submitHandler: function (form) {
            $('#Btn').addClass('btn-dis').html('缩短网址...').attr('disabled', true);
            $.post("{{url('t')}}",
                {
                    _method: "POST",
                    _token: '{{csrf_token()}}',
                    data: {'url': $("#url").val(), 'expiration': $("#expiration").val(), 'infoBak': $("#infoBak").val()}
                },
                function (data) {
                    if (data.success) {
                        //alert(data.minUrl);
                        $(document).ready(function () {
                            $("#show_1").hide();
                            $("#show_2").show();
                            $("#oldurl").text("原网址: " + data.oldUrl);
                            $("#expiration_").text("过期时间:" + data.endTime);
                            $("#minUrl").val('{!! $doMain !!}/t/'+data.minUrl);
                            $("#ksfw").attr("href", "{!! $doMain !!}/t/" + data.minUrl);
                            $("#rwm").attr("src", "http://qr.liantu.com/api.php?w=140&m=10&text={!! $doMain !!}/t/"+data.minUrl);
                        })
                        //window.location.href = '../?api-getUrl.html';
                    } else {
                        layer.msg(data.msg, {shift: 6});
                        $('#Btn').removeClass('btn-dis').html('缩短网址').attr('disabled', false);
                    }

                },);
            return false;
        }
    });
</script>
