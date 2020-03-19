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
        <td>名称</td>
        <td>{!! $name !!}</td>
    </tr>
    <tr>
        <td>密码</td>
        <td>{!! $password !!}</td>
    </tr>
</table>
<a href="{{url('test')}}">返回</a>
</body>
</html>
