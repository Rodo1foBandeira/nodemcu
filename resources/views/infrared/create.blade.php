@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Cadastrar Infravermelho</h1>
        {!! Form::open(['route'=>'infrared.store','method'=>'POST']) !!}
        @include('infrared._form')
        {!! Form::close() !!}
    </div>

@stop