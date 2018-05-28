@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Cadastrar Grupo</h1>
        {!! Form::open(['route'=>'group.store','method'=>'POST']) !!}
        @include('group._form')
        {!! Form::close() !!}
    </div>

@stop