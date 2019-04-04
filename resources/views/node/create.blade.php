@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Cadastrar NodeMcu</h1>
        {!! Form::open(['route'=>'node.store','method'=>'POST']) !!}
        @include('node._form')
        {!! Form::close() !!}
    </div>

@stop