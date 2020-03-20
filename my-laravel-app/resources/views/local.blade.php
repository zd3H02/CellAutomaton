@extends('layouts.app')


@section('style')
<link
  rel="stylesheet"
  href="https://maxcdn.bootstrapcdn.com/bootstrap/4.4.1/css/bootstrap.min.css"
  integrity="sha384-Vkoo8x4CGsO3+Hhxv8T/Q5PaXtkKtu6ug5TOeNV6gBiFeWPGFN9MuhOf23Q9Ifjh"
  crossorigin="anonymous"
/>
@endsection

@section('js')
<script>
    const G_CSRF_TOKEN    = '<?php echo csrf_token(); ?>'
    const G_LOCAL_CELL_ID = {{ $id }}
</script>
<script src="{{ asset('js//cell-automaton-app-controller.js') }}"></script>
@endsection

@section('content')
    <div id="local-app" class="local-app"></div>
@endsection
