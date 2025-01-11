@extends('layouts.app')

@section('content')

@csrf
<music-update token_crsf="{{ @csrf_token() }}" :music_edit="{{$music}}"></music-update>

@endsection
