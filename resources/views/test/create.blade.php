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
<form action="{{url('test')}}" method="post">
    <table>
        <tr>
            <td>名称</td>
            <td>密码</td>
        </tr>
        <tr>
            <td><input type="text" name="name"></td>
            <td><input type="text" name="password"></td>
        </tr>
        <tr>
            <td colspan="3"><input type="submit"></td>
        </tr>
    </table>
    {{csrf_field()}}
</form>
</body>
</html>
