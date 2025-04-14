@extends('layouts.app')

@section('content')
    <div class="container-fluid" >
        <home-list token_crsf="{{ @csrf_token() }}"></home-list>
    </div>
@endsection
