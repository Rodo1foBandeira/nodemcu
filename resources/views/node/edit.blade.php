@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>Editar NodeMcu</h1>
        {!! Form::model($node,['route'=>['node.update',$node->id],'method'=>'PUT']) !!}
        @include('node._form')
        {!! Form::close() !!}
    </div>

@stop