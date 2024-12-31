@extends('layouts.app')

@section('content')
@csrf
<show-singer-music token_crsf="{{ @csrf_token() }}" :music="{{$music}}"></show-singer-music>

@endsection
