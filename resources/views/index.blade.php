@extends('scaffold.app')

@section('page', app_name() . ' | ' . $page)

@section('content')
    <div class="card-columns" v-bind:class="{'d-none': rendered}" id="vue-body">
        <div v-for="b in bots" class="card mb-4" :id="'bot-' + b.id" v-bind:class="b.classes">
            <div class="card-body">
                <h5 class="card-title">@{{b.name}}</h5>
                <h6 class="card-subtitle mb-2 text-black-50">@@{{b.id}}</h6>
                <p class="card-text" v-html="b.description"></p>    
                <a class="btn btn-styled btn-send" :href="b.url"><i class="fas fa-paper-plane mr-2"></i>Send Message</a>
            </div>
        </div>
    </div>
@endsection

@section('after-script')
    <script src="{{ asset('js/index.js') }}"></script>  
@endsection