@php
	$url=  Route::currentRouteName();
@endphp
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

	<!-- Open Graph / Facebook / WhatsApp -->
	<meta property="og:title" content="O samba vive">
	<meta property="og:description" content="Cifras e repertórios de Samba.">
	<meta property="og:image" content="{{ asset('storage/image/osambavive.jpg') }}">
	<meta property="og:url" content="{{ url()->current() }}">
	<meta property="og:type" content="website">

	<!-- Twitter -->
	<meta name="twitter:card" content="summary_large_image">
	<meta name="twitter:title" content="O samba vive">
	<meta name="twitter:description" content="Cifras e repertórios de Samba.">
	<meta name="twitter:image" content="{{ asset('storage/image/osambavive.jpg') }}">

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">
	<link rel="icon" href="{{asset('storage/image/favicon-32x32.png')}}" type="image/x-icon">
    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

	<style>
		.list-group-item{
			background-color: #ffeee2;
			color: #89443d ;
			border: 0px;
		}
        /* Efeito de hover no item de lista */
        .list-group-item:hover {
            background-color: #ffcc99; /* Cor de fundo ao passar o mouse */
            color: #6a2d1a; /* Cor do texto */
        }

        /* Efeito de hover no link */
        .list-group-item a:hover {
            color: #6a2d1a; /* Cor do link */
            text-decoration: underline; /* Adiciona um sublinhado ao link */
        }
    </style>
</head>
<body style="background-color: #ffeee2">
    <div id="app">
        <nav class="navbar navbar-expand-lg " data-bs-theme="dark" style="background-color: #89443d">
            <div class="container-fluid">
				<a class="navbar-brand" href="{{route('home')}}">O Samba Vive</a>
				
				<button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarTogglerDemo02" aria-controls="navbarTogglerDemo02" aria-expanded="false" aria-label="Toggle navigation">
					<span class="navbar-toggler-icon"></span>
              	</button>
				
				
              	<div class="collapse navbar-collapse" id="navbarTogglerDemo02">
					<ul class="navbar-nav me-auto mb-2 mb-lg-0">
						<li class="nav-item">
							<a  class="nav-link @if($url ==  'chord' ) active @endif"  aria-current="page"  data-bs-toggle="offcanvas" href="#offcanvasExample"  aria-controls="offcanvasExample">Cifras</a>
						</li>
					@auth
							<li class="nav-item">
								<a  class="nav-link " aria-current="page" href="#">Repertórios</a>
							</li>
							<li class="nav-item">
								<a class="nav-link @if($url ==  'editor' )active @endif" href="{{route('editor')}}">Inserir musica</a>
							</li>
							<li class="nav-item">
								<a class="nav-link @if($url ==  'musico' )active @endif" href="{{route('musico')}}">Musico</a>
							</li>
							<li class="nav-item">
								<a class="nav-link @if($url ==  'acorde' )active @endif" href="{{route('acorde')}}">Acorde</a>
							</li>
							<li class="nav-item">
								<a class="nav-link @if($url ==  'tom' )active @endif" href="{{route('tom')}}">Tom</a>
							</li>
							<li class="nav-item">
								<a class="nav-link @if($url ==  'ritimo' )active @endif" href="{{route('ritimo')}}">Ritímo</a>
							</li>
						
					@endauth

				</ul>

					@auth
					<form class="d-flex" action="{{ route('logout') }}" method="POST">
						@csrf
						<button class="btn btn-danger" type="submit">Sair</button>
					</form>
					@endauth
					
              	</div>
				
            </div>
			
          </nav>
		  <div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel"  style="background-color: #89443d; color: #fff">
			<div class="offcanvas-header">
				<button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
			</div>
			<div class="offcanvas-body">
				<div>
					Nosso dicionário de acordes é construído manualmente. Caso encontre algum acorde com erro, pedimos que entre em contato conosco.
				</div>
				<br>
				
				<h5 class="offcanvas-title" id="offcanvasExampleLabel">Cifras em:</h5>
				<br>
				<ul class="list-group"  style="border: 0px">
				  <li class="list-group-item" ><a class="nav-link" href="{{route('chord',['chord' => 'C']) }}">DÓ - C</a></li>
				  <li class="list-group-item" ><a class="nav-link" href="{{route('chord',['chord' => 'D']) }}">RÉ - D</a></li>
				  <li class="list-group-item" ><a class="nav-link" href="{{route('chord',['chord' => 'E']) }}">MI - E</a></li>
				  <li class="list-group-item" ><a class="nav-link" href="{{route('chord',['chord' => 'F']) }}">FÁ - F</a></li>
				  <li class="list-group-item" ><a class="nav-link" href="{{route('chord',['chord' => 'G']) }}">SOL - G</a></li>
				  <li class="list-group-item" ><a class="nav-link" href="{{route('chord',['chord' => 'A']) }}">LÁ - A</a></li>
				  <li class="list-group-item" ><a class="nav-link" href="{{route('chord',['chord' => 'B']) }}">SI - B</a></li>
				</ul>
			 
			</div>
		  </div>
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>
</html>
