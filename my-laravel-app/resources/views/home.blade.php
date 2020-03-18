@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <form method="POST">
        @csrf
        <div class="row row-scrollable my-thumbnail-and-details">
            <div class="vh-100 col-md-2 border-right border-secondary">
                <h2>{{Auth::user()->name}}</h2>
                <button class="btn btn-success" type="submit" formaction="{{ url('home')}}">新規作成</button>
            </div>
            <div class="vh-100 col-md-3 overflow-auto">
                <h2 class="border-bottom border-secondary sticky-top">セルオートマトン一覧</h2>
                @if (isset($items))
                    <table class="table table-hover table-striped table-bordered">
                        <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td>
                                    <div class="position-relative border border-primary ">
                                        <a class="stretched-link text-decoration-none" href="{{ url('/home') . '?id=' . $item->id}}">
                                            <h3>{{$item->cell_name}}</h3>
                                            <img src="{{ asset('storage/' . $item->thumbnail_filename) }}">
                                        </a>
                                    </div>
                                    <button class="btn btn-primary" type="submit" formaction="{{ url('local')}}" name="id" value="{{$item->id}}">設定</button>
                                    <button class="btn btn-danger" type="submit" formaction="{{ url('home/del')}}" name="id" value="{{$item->id}}">削除</button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
            <div class="vh-100 col-md-7 overflow-auto">
                <h2 class="border-bottom border-secondary sticky-top">詳細</h2>
                @if (isset($detailDisplayItem))
                    <p>{{$detailDisplayItem->cell_name}}</p>
                    <p>作成日{{$detailDisplayItem->created_at}}</p>
                    <p>更新日{{$detailDisplayItem->updated_at}}</p>
                    <img src="{{ asset('storage/' . $detailDisplayItem->detail_filename) }}">
                    <p>{{$detailDisplayItem->cell_code}}</p>
                @endif
            </div>
        </div>
    </form>
</div>
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


{{-- @if (isset($items))
@foreach ($items as $item)
    <div>
        <p><a href="{{ url('/home') . '?id=' . $item->id}}">{{$item->cell_name}}</a></p>
        <img src="{{ asset('storage/' . $item->thumbnail_filename) }}">
        <button type="submit" formaction="{{ url('local')}}" name="id" value="{{$item->id}}">設定</button>
        <button type="submit" formaction="{{ url('home/del')}}" name="id" value="{{$item->id}}">削除</button>
    </div>
@endforeach
@endif --}}