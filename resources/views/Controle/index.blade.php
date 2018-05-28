@extends('layouts.app')

@section('content')
    <div class="container" id="divGroups">
        @include('controle._index')
    </div>
@stop

<script src="{{ asset('js/controle.js') }}" type="text/javascript"></script>