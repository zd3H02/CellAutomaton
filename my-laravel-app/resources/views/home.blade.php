@extends('layouts.app')

@section('js')
<script defar>
window.onload = function () {
    $('#new-create').submit(function(){
      var scroll_top = $('#list').scrollTop();
      console.log(scroll_top)
      console.log("tanuki")
      window.localStorage.setItem('is_post', true);
      window.localStorage.setItem('scrooll_top', scroll_top);
    });

    window.onload = function(){
        if (indow.localStorage.getItem('is_post')) {
            $(window).scrollTop(window.localStorage.getItem('scrooll_top'));
            window.localStorage.removeItem('is_post');
        }
    }
}
</script>

@endsection

@section('content')
<div class="container-fluid">
    <form method="POST">
        @csrf
        <div class="row row-scrollable my-thumbnail-and-details">
            <div class="my-vh-100 col-md-2 border-right border-secondary">
                <h2 class="text-center">{{Auth::user()->name}}</h2>
                <button class="btn btn-success w-100 mb-1" type="submit" formaction="{{ url('home/create')}}">新規作成</button>
                <button #id="new-create" class="btn btn-primary w-100 mb-1" type="submit" formaction="{{ url('home') }}">ライフゲーム一覧</button>
                <button class="btn btn-secondary w-100 mb-1" type="submit" formaction="#">ゴミ箱</button>
            </div>
            <div class="my-vh-100 col-md-3 overflow-auto">
                <h2 id="list" class="border-bottom border-secondary sticky-top bg-light mx-n2 text-center">一覧</h2>
                @if (isset($items))
                    @foreach ($items as $item)
                        <div class="card w-100 mb-2 mx-auto rounded">
                            <div class="row no-gutters">
                                <div class="position-relative my-card w-100">
                                    <a class="stretched-link text-decoration-none" href="{{ url('/home') . '?id=' . $item->id}}">
                                        <div class="d-flex flex-row align-items-center">
                                            <div class="col-md-4">
                                                <img class="border border-secondary m-1 rounded" src="{{ asset('storage/' . $item->thumbnail_filename) }}">
                                            </div>
                                            <div class="col-md-8">
                                                <div class="card-body">
                                                    <h5 class="card-title text-dark">{{$item->cell_name}}</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            </div>
                            <button class="btn btn-primary rounded-0" type="submit" formaction="{{ url('local')}}" name="id" value="{{$item->id}}">設定</button>
                            <button class="btn btn-secondary my_btn_round" type="submit" formaction="{{ url('home/del')}}" name="id" value="{{$item->id}}">削除</button>
                        </div>
                    @endforeach
                @endif
            </div>
            <div class="my-vh-100 col-md-7 overflow-auto">
                <h2 class="border-bottom border-secondary sticky-top bg-light mx-n2 text-center">詳細</h2>
                @if (isset($detailDisplayItem))
                    <pre>名称      ：{{$detailDisplayItem->cell_name}}</pre>
                    <pre>作成日    ：{{$detailDisplayItem->created_at}}</pre>
                    <pre>最終更新日：{{$detailDisplayItem->updated_at}}</pre>
                    <img class="border border-secondary m-1 rounded" src="{{ asset('storage/' . $detailDisplayItem->detail_filename) }}">
                    <p>コード：</p>
                    <pre>{{$detailDisplayItem->cell_code}}</pre>
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




{{--             <div class="my-vh-100 col-md-3 overflow-auto">
                <h2 class="border-bottom border-secondary sticky-top bg-light">セルオートマトン一覧</h2>
                @if (isset($items))
                    <table class="table table-striped table-bordered">
                        <tbody>
                        @foreach ($items as $item)
                            <tr>
                                <td class="p-0">
                                    <div class="position-relative border vw-100">
                                        <a class="stretched-link text-decoration-none" href="{{ url('/home') . '?id=' . $item->id}}">
                                            <h3  class="border-bottom">{{$item->cell_name}}</h3>
                                            <img src="{{ asset('storage/' . $item->thumbnail_filename) }}" class="rounded">
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
            </div> --}}