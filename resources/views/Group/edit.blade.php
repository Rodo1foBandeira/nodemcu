@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Editar Grupo</h1>
        {!! Form::model($group,['route'=>['group.update',$group->id],'method'=>'PUT']) !!}
        @include('group._form')
        {!! Form::close() !!}
    </div>

@stop