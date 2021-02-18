@extends('scaffold.app')

@section('page', app_name() . ' | ' . $page)

@if ($verify == 'true')
    @section('content')
        @include('components.alerts')
        @include('components.warning-jumbotron')
        @include('components.test-textarea')
        @include('components.test-bar')
    @endsection

    @section('after-script')
        <script src="{{ asset('js/bot-test.js') }}"></script>  
    @endsection
@else
    @section('content')
        @include('auth.totp')
    @endsection

    @section('after-script')
        <script src="{{ asset('js/bot-verify.js') }}"></script>  
    @endsection
@endif