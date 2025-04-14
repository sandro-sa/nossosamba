@extends('layouts.app')

@section('content')
@csrf

    <show-chord token_crsf="{{ @csrf_token() }}" :chords="{{$chords}}"></show-chord>
@endsection
