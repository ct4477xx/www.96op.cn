<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{!! $title !!}</title>
</head>
<body>
<table>
    <tr>
        <td>ID</td>
        <td>名称</td>
        <td>操作</td>
    </tr>
    @foreach($data as $v)
    <tr>
        <td>{{$v['id']}}</td>
        <td>{{$v['name']}}</td>
        <td></td>
    </tr>
    @endforeach
    <tr>
        <td colspan="3">
            添加
        </td>
    </tr>
</table>

</body>
</html>
