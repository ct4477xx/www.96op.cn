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
<form action="{{url('test/'.$id)}}" method="post">
    <table>
        <tr>
            <td>名称</td>
            <td>密码</td>
        </tr>
        <tr>
            <td><input type="text" name="name" value="{!! $name !!}"></td>
            <td><input type="text" name="password" value=""></td>
        </tr>
        <tr>
            <td colspan="3"><input type="submit"></td>
            <input type="hidden" name="id" value="{!! $id !!}">
        </tr>
    </table>
    {{csrf_field()}}
    {{method_field('PATCH')}}
</form>
</body>
</html>
