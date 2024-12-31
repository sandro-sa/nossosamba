@extends('layouts.app')

@section('content')
@csrf
<tone token_crsf="{{ @csrf_token() }}"></tone>

@endsection
