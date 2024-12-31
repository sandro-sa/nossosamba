@extends('layouts.app')

@section('content')
@csrf
<show-singer-musics token_crsf="{{ @csrf_token() }}"  :singer="{{$singer}}"></show-singer-musics>
@endsection
