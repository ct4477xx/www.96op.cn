<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{!! $title !!}</title>
    @include('tool.test.public.js')
</head>
<body>
<table>
    <tr>
        <td>编号</td>
        <td>名称</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
        <tr>
            <td>{!! $v['id'] !!}</td>
            <td>{!! $v['name'] !!}</td>
            <td><a href="{{url('test/'.$v['id'].'/edit')}}">修改</a> | <a href="javascript:;"
                                                                        onclick="is_del(this,{!! $v['id'] !!})">删除</a> |
                <a href="{{url('test/'.$v['id'])}}">预览</a></td>
        </tr>
    @endforeach
    <tr>
        <td colspan="3"><a href="test/create">添加</a></td>
    </tr>
    <tr>
        <td colspan="3"> {{ $data->links() }} </td>
    </tr>
</table>
<script>
    function is_del(obj,id) {
        layer.confirm('确定删除？', {
            btn: ['删除', '取消'] //按钮
        }, function () {
            //layer.msg('的确很重要', {icon: 1});
            $.post("test/" + id, {_method: 'DELETE',_token:'{{csrf_token()}}'},
                function (data) {
                    if (data.success==0) {
                        $(obj).parents('tr').remove();
                        layer.msg(data.msg, {icon: 6, time: 1000});
                    }
                });
        });
    }
</script>
</body>
</html>
