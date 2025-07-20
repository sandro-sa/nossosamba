@extends('layouts.app')

@section('content')
@csrf
    <create-repertoire token_crsf="{{ @csrf_token()}}" ></create-repertoire>
@endsection
