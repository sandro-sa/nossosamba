@extends('layouts.app')

@section('content')
@csrf
<music-create token_crsf="{{ @csrf_token() }}"></music-create>

@endsection
