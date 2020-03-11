@extends('layouts.app')

@section('js')
<script defer>
    const G_CSRF_TOKEN    = '<?php echo csrf_token(); ?>'
    const G_LOCAL_CELL_ID = {{ $id }}
</script>
<script src="{{ asset('js//cell-automaton-app-controller.js') }}" defer></script>
@endsection

@section('content')
    <div class="container">
        <div id="local-app" class="local-app"></div>
    </div>
@endsection
