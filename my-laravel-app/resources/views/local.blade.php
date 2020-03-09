@extends('layouts.app')

@section('js')
<script defer>
    const G_CSRF_TOKEN    = '<?php echo csrf_token(); ?>'
    const G_LOCAL_CELL_ID = {{ $id }}
</script>
<script src="{{ asset('js//cell-automaton-app-controller.js') }}" defer></script>
@endsection

@section('content')
    <div id="local-app"></div>
    @auth
        <a href="{{ url('/home') }}">Home</a>
    @else
        <a href="{{ route('login') }}">Login</a>
    @endauth
@endsection
