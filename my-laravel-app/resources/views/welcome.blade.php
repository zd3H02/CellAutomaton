<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>LifeEvo</title>

        <!-- Scripts -->
        <script src="{{ asset('js/app.js') }}" defer></script>

        <!-- Fonts -->
        {{-- <link href="https://fonts.googleapis.com/css?family=Nunito:200,600" rel="stylesheet"> --}}

        <!-- Styles -->
        <link href="{{ asset('css/style.css') }}" rel="stylesheet">
    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark sticky-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">LifeEvo</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item active">
                            <a class="nav-link" href="#">ライフゲームとは<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item active">
                            <a class="nav-link" href="#">ライフゲームのルール<span class="sr-only">(current)</span></a>
                        </li>
                    </ul>
                    <ul class="navbar-nav">
                        @if (Route::has('login'))
                            @auth
                                <li class="nav-item active">
                                    <a href="{{ url('/home') }}" class="nav-link">Home</a>
                                </li>
                            @else
                                <li class="nav-item active">
                                    <a href="{{ route('login') }}" class="nav-link">Login</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item active">
                                        <a href="{{ route('register') }}" class="nav-link">Register</a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">

            <div id="carouselExampleFade" class="carousel slide carousel-fade" data-interval="500" data-pause="false" data-ride="carousel">

                <div class="carousel-inner">
                    <div class="carousel-caption col-6 offset-3">
                        <h1 class="display-1 text-muted">LifeEvo</h1>
                        <p class="text-muted">
                            ライフゲームは生命の誕生、進化、淘汰などのプロセスを簡易的なモデルで再現したシミュレーションゲームです。<br>
                            LifeEvoは手軽にライフゲームが遊べるWebアプリです。<br>
                            ルールの変更もできるのでオリジナルのライフゲームが楽しめます。<br>
                            また、ほかのユーザーが作って投稿したライフゲームを遊ぶこともできます。
                        </p>
                    </div>
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="{{ asset('storage/main/' . 'ginga_00.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('storage/main/' . 'ginga_01.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('storage/main/' . 'ginga_02.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('storage/main/' . 'ginga_03.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('storage/main/' . 'ginga_04.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('storage/main/' . 'ginga_05.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('storage/main/' . 'ginga_06.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('storage/main/' . 'ginga_07.jpg') }}">
                    </div>
                </div>
            </div>

            <div class="container">
                <div>
                    ライフゲームとは
                    ライフゲームはセル・オートマトン（格子状のセルと単純な規則によって記述される離散計算モデル）の
                    一種で1970年にイギリスの数学者ジョン・ホートン・コンウェイが考案した
                    生命の誕生、進化、淘汰などのプロセスを簡易的なモデルで再現したシミュレーションゲームです。
                    単純なルールで複雑で神秘的な模様の変化を楽しめるため、パズルの要素も持っています。
                    ライフゲームやセル・オートマトンは1970～1980年代にコンピュータコミュニティにより盛んに研究が行われました。
                    興味深いことにライフゲームはチューリング完全であることが証明されています。
                    このことはライフゲームで計算機で実行可能な全ての計算について、対応する模様(パターン)を作ることができるということを表しています。
                    近年はAlife(人工生命)分野の盛り上がりから再び注目されています。
                </div>

                <div>
                    ライフゲームのルール
                    1970年にジョン・ホートン・コンウェイが考案したライフゲームのルールは以下のようなものでした。
                    ライフゲームは碁盤のような格子があり、この1つの格子がセルと呼ばれます。セルには生と死の2つの状態があり、
                    生が黒色、死が白色に塗られます。あるセルの次の状態は近隣の8つのセルから決まります。

                    セルの生死は次のルールによって決まります。
                    ・誕生
                    死んでいるセルに隣接する生きたセルがちょうど3つあれば、次の世代が誕生する。
                    ・生存
                    生きているセルに隣接する生きたセルが2つか3つならば、次の世代でも生存する。
                    ・過疎
                    生きているセルに隣接する生きたセルが1つ以下ならば、過疎により死滅する。
                    ・過密
                    生きているセルに隣接する生きたセルが4つ以上ならば、過密により死滅する。

                    たったこれだけのルールから複雑で神秘的な模様が生み出されるこがライフゲームの魅力です。

                    LifeEvoではセルの色、ルール、ルール判定に使用するセルの位置など自分好みにカスタマイズ可能です。
                    ぜひ、自分だけのライフゲームを作ってみてください。
                </div>
            </div>
        </div>
        {{-- {{phpinfo()}} --}}
    </body>
</html>


















{{--




<div class="links">
    <a href="https://laravel.com/docs">Docs</a>
    <a href="https://laracasts.com">Laracasts</a>
    <a href="https://laravel-news.com">News</a>
    <a href="https://blog.laravel.com">Blog</a>
    <a href="https://nova.laravel.com">Nova</a>
    <a href="https://forge.laravel.com">Forge</a>
    <a href="https://vapor.laravel.com">Vapor</a>
    <a href="https://github.com/laravel/laravel">GitHub</a>
</div> --}}


