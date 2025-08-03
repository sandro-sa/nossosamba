@extends('layouts.app')

@section('content')
@csrf
    <show-repertoire token_crsf="{{ @csrf_token()}}"  :musics='@json($musics_array)'> ></show-repertoire>
@endsection
