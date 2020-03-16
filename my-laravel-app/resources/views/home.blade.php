@extends('layouts.app')

@section('content')
<form method="POST">
    @csrf
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div>
                    {{Auth::user()->name}}:セルオートマトン一覧
                    <button type="submit" formaction="{{ url('home')}}">新規作成</button>
                </div>
                @if (isset($items))
                @foreach ($items as $item)
                    <div>
                        <p><a href="{{ url('/home') . '?id=' . $item->id}}">{{$item->cell_name}}</a></p>
                         <img src="{{ asset('storage/' . $item->thumbnail_filename) }}">
                        <button type="submit" formaction="{{ url('local')}}" name="id" value="{{$item->id}}">設定</button>
                        <button type="submit" formaction="{{ url('home/del')}}" name="id" value="{{$item->id}}">削除</button>
                    </div>
                @endforeach
            @endif
            </div>
            <div class="col-md-6">
                <p>詳細</p>
                @if (isset($detailDisplayItem))
                    <p>{{$detailDisplayItem->cell_name}}</p>
                    <p>{{$detailDisplayItem->created_at}}</p>
                    <p>{{$detailDisplayItem->updated_at}}</p>
                    <img src="{{ asset('storage/' . $detailDisplayItem->detail_filename) }}">
                    <p>{{$detailDisplayItem->cell_code}}</p>
                @endif
            </div>
        </div>
    </div>
</form>
@endsection




{{-- <input type="hidden" name="creator" value="{{Auth::user()->name}}"> --}}

{{-- <div><a href="{{ url('/world') }}">Worldへ行く</a></div> --}}



{{-- @extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in!
                </div>
            </div>
            <div><a href="{{ url('/world') }}">Worldへ行く</a></div>
            <form method="POST">
                @csrf
                {{-- <input type="hidden" name="creator" value="{{Auth::user()->name}}"> --}}
                {{-- <button type="submit" formaction="{{ url('home')}}">新規作成</button>
                @if (isset($items))
                    @foreach ($items as $item)
                        <p>
                            {{Auth::user()->name}}:
                            <button type="submit" formaction="{{ url('local')}}" name="id" value="{{$item->id}}">設定</button>
                            <button type="submit" formaction="{{ url('home/del')}}" name="id" value="{{$item->id}}">削除</button>
                        </p>
                    @endforeach
                @endif
            </form>
        </div>
    </div>
</div> --}}
{{-- @endsection --}}