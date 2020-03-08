<!DOCTYPE html>
<html lang="ja">
<head>
    {{-- <link rel="stylesheet" href="{{ asset('css/app.css')}}"> --}}
    <meta charset="UTF-8">
    <title>local cell</title>
</head>
<body>
    {{-- <form method="POST">
        @csrf
        <textarea name="code" id="code" cols="30" rows="10"></textarea>
        <button id="run_button" type="submit" formaction="{{ url('local/run')}}" name="run" value="true">実行</button>
        <button id="run_button" type="submit" formaction="{{ url('local/stop')}}" name="stop" value="true">停止</button>
        <button id="run_button" type="submit" formaction="{{ url('local/save')}}" name="save" value="true">保存</button>
    </form> --}}
    <div id="local-app"></div>
    {{-- <div id="root"></div> --}}
    {{-- <p class="num"></p> --}}
    {{-- <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script> --}}
    {{-- <input type="hidden" name="id" value="{{ $id }}"> --}}
    <script>
        let csrf_token = '<?php echo csrf_token(); ?>'
        let testid = {{ $id }}
    </script>
    <script src="{{ asset('js//local-app.js') }}"></script>
</body>
</html>