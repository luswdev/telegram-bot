@extends('scaffold.app')

@section('page', app_name() . ' | ' . $page)

@if ($verify == 'true')
    @section('content')
            @include('components.datePicker')
            @include('components.tab-nav')
            @include('components.result-tab')
            @include('components.log-modal')
            @include('components.hljs-buffer') 
    @endsection

    @section('after-script')
        <script src="{{ asset('js/bot-log.js') }}"></script>  
    @endsection
@else
    @section('content')
        @include('auth.totp')
    @endsection

    @section('after-script')
        <script src="{{ asset('js/bot-verify.js') }}"></script>  
    @endsection
@endif