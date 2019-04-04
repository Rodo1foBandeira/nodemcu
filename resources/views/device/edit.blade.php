@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Editar Controle</h1>
        {!! Form::model($device,['route'=>['device.update',$device->id],'method'=>'PUT']) !!}
        @include('device._form')
        {!! Form::close() !!}
    </div>

@stop