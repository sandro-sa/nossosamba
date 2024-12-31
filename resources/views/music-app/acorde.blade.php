@extends('layouts.app')

@section('content')
@csrf
<chord-create token_crsf="{{ @csrf_token() }}"></chord-create>

@endsection
