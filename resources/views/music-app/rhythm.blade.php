@extends('layouts.app')

@section('content')
@csrf
<rhythm token_crsf="{{ @csrf_token() }}"></rhythm>

@endsection
