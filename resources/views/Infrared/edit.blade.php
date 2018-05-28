@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Editar Infravermelho</h1>
        {!! Form::model($infrared,['route'=>['infrared.update',$infrared->id],'method'=>'PUT']) !!}
        @include('infrared._form')
        {!! Form::close() !!}
    </div>

@stop