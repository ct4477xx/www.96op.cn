<!DOCTYPE html>
<html class="x-admin-sm">

<head>
    <meta charset="UTF-8">
    <title>{!! home()['title'] !!}}</title>
    <meta name="renderer" content="webkit">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    <meta name="viewport"
          content="width=device-width,user-scalable=yes, minimum-scale=0.4, initial-scale=0.8,target-densitydpi=low-dpi"/>
    @include('.sys.pubilc.css')
    @include('.sys.pubilc.js')
</head>

<body>
<div class="layui-fluid">
    <div class="layui-row">
        <form class="layui-form">
            <div class="layui-form-item">
                <label for="name" class="layui-form-label">
                    <span class="x-red">*</span>姓名</label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" required="" lay-verify="required"
                           {!! $show?'disabled':'' !!}
                           autocomplete="off" class="layui-input" value="{!! $name?$name:'' !!}"></div>
            </div>
            <div class="layui-form-item">
                <label for="mobile" class="layui-form-label">
                    <span class="x-red">*</span>手机</label>
                <div class="layui-input-inline">
                    <input type="text" id="mobile" name="mobile" required="" lay-verify="required|phone"
                           {!! $show?'disabled':'' !!}
                           autocomplete="off"
                           class="layui-input" value="{!! $mobile?$mobile:'' !!}"></div>
            </div>
            <div class="layui-form-item">
                <label for="idCardNo" class="layui-form-label">
                    <span class="x-red">*</span>身份证号</label>
                <div class="layui-input-inline">
                    <input type="text" id="idCardNo" name="idCardNo" required="" lay-verify="required|identity"
                           {!! $show?'disabled':'' !!}
                           autocomplete="off"
                           class="layui-input" value="{!! $idCardNo?$idCardNo:'' !!}"></div>
            </div>
            <div class="layui-form-item">
                <label for="address" class="layui-form-label">
                    <span class="x-red">*</span>家庭地址</label>
                <div class="layui-input-inline">
                    <input type="text" id="address" name="address" required="" lay-verify="required"
                           {!! $show?'disabled':'' !!}
                           autocomplete="off" class="layui-input" value="{!! $address?$address:'' !!}"></div>
            </div>
            <div class="layui-form-item">
                <label for="house" class="layui-form-label">
                    <span class="x-red">*</span>楼层</label>
                <div class="layui-input-inline">
                    <select id="house" name="house" lay-filter="house" class="valid"
                            lay-verify="required" {!! $show?'':'' !!}>
                        <option placeholder="请选择楼层"></option>
                        @foreach(getHouse() as $v)
                            <option value="{!! $v['id'] !!}" {!! $house==$v['id']?'selected':'' !!}>{!! $v['name'] !!}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="houseRoom" class="layui-form-label">
                    <span class="x-red">*</span>房间号</label>
                <div class="layui-input-inline">
                    <select id="houseRoom" name="houseRoom" lay-filter="houseRoom" class="valid"
                            lay-verify="required" {!! $show?'':'' !!}>
                        <option placeholder="请选择房间"></option>
                        @if($id)
                            @foreach(getHouseRoom($house) as $v)
                                <option value="{!! $v['id'] !!}" {!! $houseRoom==$v['id']?'selected':'' !!}>{!!
                                    $v['name']
                                    !!}
                                </option>
                            @endforeach
                        @endif
                    </select>
                </div>
            </div>
            <div class="layui-form-item">
                <label for="remarks" class="layui-form-label">
                    <span class="x-red">*</span>备注信息</label>
                <div class="layui-input-inline" style="width: 620px">
                    <textarea style="display: none;" type="text" id="remarks" name="remarks" required=""
                              autocomplete="off" class="layui-input">{!! $remarks?$remarks:'' !!}
                    </textarea>
                </div>
            </div>
            @if($show=='')
                <div class="layui-form-item">
                    <label for="add" class="layui-form-label"></label>
                    <button class="layui-btn" lay-filter="add" lay-submit="">确定</button>
                </div>
            @endif
            <input type="hidden" name="id" id="id" value="{!! $id !!}">
        </form>
    </div>
</div>

<script>layui.use(['layedit', 'form', 'layer', 'jquery'],
        function () {
            let $ = layui.jquery;
            let form = layui.form,
                layer = layui.layer,
                layedit = layui.layedit;

            layedit.set({
                //暴露layupload参数设置接口 --详细查看layupload参数说明
                uploadImage: {
                    url: '/Attachment/LayUploadFile',
                    accept: 'image',
                    acceptMime: 'image/*',
                    exts: 'jpg|png|gif|bmp|jpeg',
                    size: '10240'
                }
                , uploadVideo: {
                    url: '/Attachment/LayUploadFile',
                    accept: 'video',
                    acceptMime: 'video/*',
                    exts: 'mp4|flv|avi|rm|rmvb',
                    size: '20480'
                }
                //右键删除图片/视频时的回调参数，post到后台删除服务器文件等操作，
                //传递参数：
                //图片： imgpath --图片路径
                //视频： filepath --视频路径 imgpath --封面路径
                , calldel: {
                    url: '/Attachment/DeleteFile'
                }
                //开发者模式 --默认为false
                , autoSync: true
                , devmode: true
                //插入代码设置
                , codeConfig: {
                    hide: true,  //是否显示编码语言选择框
                    default: 'javascript' //hide为true时的默认语言格式
                }
                , tool: [
                    'html', 'code', 'strong', 'italic', 'underline', 'del', 'addhr', '|', 'fontFomatt', 'colorpicker', 'face'
                    , '|', 'left', 'center', 'right', '|', 'link', 'unlink', 'images', 'image_alt', 'video', 'anchors'
                    , '|', 'table', 'fullScreen'
                ]
                , height: '90%'
            });

            let index = layedit.build('remarks');

            //自定义验证规则
            form.verify({
                name: function (value) {
                    if (value.length > 5 || value.length < 1) {
                        return '姓名不得少于1位或多于5位';
                    }
                },
                onchange: function (index) {
                    console.log(index);
                }
            });

            //监听提交
            form.on('submit(add)',
                function (data) {
                    $.post("{{url('sys/tenant/')}}",
                        {
                            _method: "POST",
                            _token: '{{csrf_token()}}',
                            data: data.field
                            // data: {
                            //     'name': data.field.name,
                            //     'bySort': data.field.bySort,
                            //     'isLock': data.field.isLock ? 0 : 1,
                            // }
                        }, function (data) {
                            if (data.success) {
                                layer.alert(data.msg, {
                                        icon: 1
                                    },
                                    function () {
                                        //关闭当前frame
                                        xadmin.close();
                                        // 可以对父窗口进行刷新
                                        xadmin.father_reload();
                                    });
                            } else {
                                layer.alert(data.msg, {
                                    icon: 5
                                });
                            }
                        });
                    return false;
                });

            //房间号选择
            form.on('select(house)',
                function (data) {
                    //console.log(data);
                    $.post("{{url('/ajaxReadKey/houseRoom')}}/" + data.value,
                        {
                            _method: "GET",
                            _token: '{{csrf_token()}}'
                        },
                        function (data) {
                            $("#houseRoom").html(data);
                            form.render();
                        });
                    return false;
                });
        });
</script>
</body>

</html>
