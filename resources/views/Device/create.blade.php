@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Cadastrar Controle</h1>
        {!! Form::open(['route'=>'device.store','method'=>'POST']) !!}
        @include('device._form')
        {!! Form::close() !!}
    </div>

@stop