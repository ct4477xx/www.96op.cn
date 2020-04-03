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
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>楼层
                </label>
                <div class="layui-input-inline">
                    <input type="text" required="" value="{!! $fatherName?$fatherName:$name !!}" lay-verify="required" disabled
                           autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>请输入栏目名称
                </div>
            </div>
            <div class="layui-form-item">
                <label for="username" class="layui-form-label">
                    <span class="x-red">*</span>房间号
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="name" name="name" required="" value="{!! $fatherName?$name:'' !!}" lay-verify="required"
                           autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>请输入栏目名称
                </div>
            </div>
            <div class="layui-form-item">
                <label for="phone" class="layui-form-label">
                    <span class="x-red">*</span>排序
                </label>
                <div class="layui-input-inline">
                    <input type="text" id="bySort" name="bySort" required="" value="0"
                           lay-verify="required|bySort"
                           autocomplete="off" class="layui-input">
                </div>
                <div class="layui-form-mid layui-word-aux">
                    <span class="x-red">*</span>数字越大排序越靠前, 最大数值为1000
                </div>
            </div>
            <div class="layui-form-item">
                <label for="" class="layui-form-label" style="margin-top: -8px;">
                    <span class="x-red">*</span>锁定状态
                </label>
                <div class="layui-input-inline">
                    <input type="checkbox" name="isLock" value="0" {!! $isLock?'':'checked' !!}  lay-text="正常|锁定"
                           autocomplete="off" lay-skin="switch">
                </div>
            </div>

            <div class="layui-form-item">
                <label for="L_repass" class="layui-form-label">
                </label>
                <button class="layui-btn" lay-filter="add" lay-submit="">
                    确定
                </button>
            </div>
        </form>
    </div>
</div>
<script>layui.use(['form', 'layer'],
        function () {
            $ = layui.jquery;
            var form = layui.form,
                layer = layui.layer;

            //自定义验证规则
            form.verify({
                bySort: function (value) {
                    if (value > 1000) {
                        return '排序最大值请控制在1000以内';
                    }
                },
            });

            //监听提交
            form.on('submit(add)',
                function (data) {
                    //console.log(data);
                    $.post("{{url('sys/houseRoom/')}}",
                        {
                            _method: "PATCH",
                            _token: '{{csrf_token()}}',
                            data: {
                                @if($fatherName)
                                'sid':0,
                                'id':'{!! $id !!}',
                                @else
                                'sid':1,
                                'fatherId':'{!! $id !!}',
                                @endif
                                'name': data.field.name,
                                'bySort': data.field.bySort,
                                'isLock': data.field.isLock ? 0 : 1,
                            }
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
                                    icon: 4
                                });
                            }
                        });
                    return false;
                });

        });</script>
</body>

</html>
