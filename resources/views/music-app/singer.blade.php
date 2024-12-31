@extends('layouts.app')

@section('content')
@csrf
<singer token_crsf="{{ @csrf_token() }}"></singer>

@endsection
