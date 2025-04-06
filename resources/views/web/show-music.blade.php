@extends('layouts.app')

@section('content')
@csrf
    <show-music token_crsf="{{ @csrf_token() }}" 
    :music="{{$music}}"
    :chords="{{$chords}}"
    tons="{{$listaDeTons}}"
    :lista_de_novos_acordes="{{$listaDeAcordeParaMudarTom}}"
    mudou_tom="{{$novoTom}}"
    ></show-music>
@endsection
