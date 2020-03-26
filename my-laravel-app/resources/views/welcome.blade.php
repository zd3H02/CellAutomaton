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

        <!-- Favicon Generator-->
        <link rel="apple-touch-icon" sizes="180x180" href="{{ asset('/image/fabicon/' . 'apple-touch-icon.png') }}">
        <link rel="icon" type="image/png" sizes="32x32" href="{{ asset('/image/fabicon/' . 'favicon-32x32.png') }}">
        <link rel="icon" type="image/png" sizes="16x16" href="{{ asset('/image/fabicon/' . 'favicon-16x16.png') }}">
        <link rel="manifest" href="{{ asset('/image/fabicon/' . 'site.webmanifest') }}">
        <link rel="mask-icon" href="{{ asset('/image/fabicon/' . 'safari-pinned-tab.svg') }}" color="#5bbad5">
        <meta name="msapplication-TileColor" content="#da532c">
        <meta name="theme-color" content="#ffffff">


    </head>
    <body>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">LifeEvo</a>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mr-auto">
                        <li class="nav-item">
                            <a class="nav-link" href="#how-to-lifegame">ライフゲームとは<span class="sr-only">(current)</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#lifegame-rule">ライフゲームのルール<span class="sr-only">(current)</span></a>
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
                                    <a href="{{ route('login') }}" class="nav-link">ログイン</a>
                                </li>
                                @if (Route::has('register'))
                                    <li class="nav-item active">
                                        <a href="{{ route('register') }}" class="nav-link">登録</a>
                                    </li>
                                @endif
                            @endauth
                        @endif
                    </ul>
                </div>
            </div>
        </nav>
        <div class="content">
            <div id="carouselExampleFade" class="carousel slide carousel-fade position-relative" data-interval="3000" data-pause="false" data-ride="carousel">
                <div class="d-none d-lg-block carousel-caption position-absolute my-center my-triangle">
                    <div class="position-absolute my-center w-100">
                        <h1 class="display-1 text-muted">LifeEvo</h1>
                        <p class="text-muted">
                            ライフゲームは生命の誕生、進化、淘汰などのプロセスを
                            <br>簡易的なモデルで再現したシミュレーションゲームです。
                            <br>LifeEvoは手軽にライフゲームが遊べるWebアプリです。
                            <br>ルールの変更もできるのでオリジナルのライフゲームが楽しめます。
                        </p>
                    </div>
                </div>
                <div class="d-none d-md-block d-lg-none carousel-caption position-absolute my-center my-triangle">
                    <div class="position-absolute my-center w-100">
                        <h1 class="display-3 text-muted">LifeEvo</h1>
                        <p class="text-muted">
                            ライフゲームは生命の誕生、進化、淘汰などのプロセスを
                            簡易的なモデルで再現したシミュレーションゲームです。
                            LifeEvoは手軽にライフゲームが遊べるWebアプリです。
                            ルールの変更もできるのでオリジナルのライフゲームが楽しめます。
                        </p>
                    </div>
                </div>
                <div class="d-none d-sm-block d-md-none carousel-caption position-absolute my-center">
                    <div class="position-absolute my-center w-100">
                        <h1 class="display-4 text-muted">LifeEvo</h1>
                        <p class="text-muted">
                            ライフゲームは生命の誕生、進化、淘汰などのプロセスを
                            簡易的なモデルで再現したシミュレーションゲームです。
                            LifeEvoは手軽にライフゲームが遊べるWebアプリです。
                            ルールの変更もできるのでオリジナルのライフゲームが楽しめます。
                        </p>
                    </div>
                </div>
                <div class="d-block d-sm-none carousel-caption position-absolute my-center">
                    <h1 class="display-5 text-muted">LifeEvo</h1>
                </div>
                <div id="carousel-inner" class="carousel-inner">
                    <div class="carousel-item active">
                        <img class="d-block w-100" src="{{ asset('/image/main/' . 'ginga_00.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('/image/main/' . 'ginga_01.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('/image/main/' . 'ginga_02.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('/image/main/' . 'ginga_03.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('/image/main/' . 'ginga_04.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('/image/main/' . 'ginga_05.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('/image/main/' . 'ginga_06.jpg') }}">
                    </div>
                    <div class="carousel-item">
                        <img class="d-block w-100" src="{{ asset('/image/main/' . 'ginga_07.jpg') }}">
                    </div>
                </div>
            </div>
            <div class="d-block d-sm-none mx-auto">
                <p class="text-muted px-4">
                    ライフゲームは生命の誕生、進化、淘汰などのプロセスを
                    簡易的なモデルで再現したシミュレーションゲームです。
                    LifeEvoは手軽にライフゲームが遊べるWebアプリです。
                    ルールの変更もできるのでオリジナルのライフゲームが楽しめます。
                </p>
            </div>
            <div class="w-100 bg-light my-5 py-5">
                <div class="container">
                    <div class="mx-auto w-75">
                        <h2 id="how-to-lifegame" class="my-anchor-pos-adj">ライフゲームとは</h2>
                        <div class="pt-2 text-left">
                            <p>
                                ライフゲームはセル・オートマトン（格子状のセルと単純な規則によって記述される離散計算モデル）の一種で
                                1970年にイギリスの数学者ジョン・ホートン・コンウェイが考案した
                                生命の誕生、進化、淘汰などのプロセスを簡易的なモデルで再現したシミュレーションゲームです。
                            </p>
                            <p>
                                単純なルールで複雑で神秘的な模様の変化を楽しめるため、パズルの要素も持っています。
                                ライフゲームやセル・オートマトンは1970～1980年代にコンピュータコミュニティにより盛んに研究が行われました。
                            </p>
                            <p>
                                興味深いことにライフゲームはチューリング完全であることが証明されています。
                                このことはライフゲームでは計算機で実行可能な全ての計算について、対応する模様(パターン)を作ることができるということを表しています。
                            </p>
                        </div>
                     </div>
                 </div>
             </div>
             <div class="w-100 bg-white mt-5 pt-5">
                 <div class="container">
                    <div class="mx-auto w-75">
                        <h2 id="lifegame-rule" class="my-anchor-pos-adj">ライフゲームのルール</h2>
                        <div class="pt-2 text-left">
                            <p>
                                1970年にジョン・ホートン・コンウェイが考案したライフゲームのルールは以下のようなものでした。
                            </p>
                            <p>
                                ライフゲームは碁盤のような格子があり、この1つの格子がセルと呼ばれます。セルには生と死の2つの状態があり、
                                生が黒色、死が白色に塗られます。あるセルの次の状態は近隣の8つのセルから決まります。
                            </p>
                            <p class="pt-5 pb-2 text-center">
                                セルの生死は次のルールによって決まります。
                            </p>
                        </div>
                        <h5 class="mt-5">誕生</h5>
                        <p>
                            死んでいるセルに隣接する生きたセルがちょうど3つあれば、
                            <br>次の世代が誕生します。
                            <br>この場合は中央の灰色のセルが黒色になります。
                        </p>
                        <img class="d-block img-thumbnail mx-auto" src="{{ asset('image/main/' . 'tanjyou.jpg') }}">
                        <h5  class="mt-5">生存</h5>
                        <p>
                            生きているセルに隣接する生きたセルが2つか3つならば、
                            <br>次の世代でも生存します。
                            <br>この場合はセルの色は変化しません。
                        </p>
                        <img class="d-block img-thumbnail mx-auto" src="{{ asset('image/main/' . 'iji.jpg') }}">
                        <h5 class="mt-5">過疎</h5>
                        <p>
                            生きているセルに隣接する生きたセルが1つ以下ならば、
                            <br>過疎により死滅します。
                            <br>この場合は中央の灰色のセルが白色になります。
                        </p>
                        <img class="d-block img-thumbnail mx-auto" src="{{ asset('image/main/' . 'kaso.jpg') }}">
                        <h5 class="mt-5">過密</h5>
                        <p>
                            生きているセルに隣接する生きたセルが4つ以上ならば、
                            <br>過密により死滅します。
                            <br>この場合は中央の灰色のセルが白色になります。
                        </p>
                        <img class="d-block img-thumbnail mx-auto" src="{{ asset('image/main/' . 'kamitsu.jpg') }}">
                        <div class="mt-5 text-left">
                            <p>
                                たったこれだけのルールから複雑で神秘的な模様が生み出されることがライフゲームの魅力です。
                                LifeEvoではセルの色、ルール、ルール判定に使用するセルの位置など、自分好みにカスタマイズ可能です。
                            </p>
                            <p class="pt-2 pb-5 text-center">
                                ぜひ、自分だけのライフゲームを作ってみてください。
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <footer class="text-center bg-dark pt-4 pb-1">
                <a class="text-decoration-none text-light pt-4" href="#">▲Top</a>
                <p class="text-light pt-4"><small>Copyright &copy; 2020 zd3H02. All Rights Reserved.</small></p>
        </footer>
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


