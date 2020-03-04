<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>local cell</title>
</head>
<body>
    
    <form method="POST">
        @csrf
        <textarea name="code" id="code" cols="30" rows="10"></textarea>
        <button id="run_button" type="submit" formaction="{{ url('local/run')}}" name="run" value="true">実行</button>
        <button id="run_button" type="submit" formaction="{{ url('local/stop')}}" name="stop" value="true">停止</button>
        <button id="run_button" type="submit" formaction="{{ url('local/save')}}" name="save" value="true">保存</button>
    </form>
    <p class="num"></p>
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="{{ asset('js//local.js') }}"></script>
</body>
</html>