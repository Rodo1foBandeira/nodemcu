@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Infravermelhos</h1>
        <a class="btn btn-primary btn-xs pull-right" href="{{route('infrared.create')}}">Cadastrar</a>
        <table id="table" class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($infrareds as $infrared)
                <tr>
                    <td>{{$infrared->id}}</td>
                    <td>{{$infrared->name}}</td>
                    <td>
                        <div class="form-inline">
                            <a class="btn btn-success btn-xs" href="{{route('infrared.show', $infrared->id)}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                            <a class="btn btn-warning btn-xs" href="{{route('infrared.edit', $infrared->id)}}"><span class="glyphicon glyphicon-pencil"></span></a>
                            {{ Form::open(['route' => ['infrared.destroy', $infrared->id], 'method' => 'delete', 'onsubmit' => "return confirm(&quot;Deseja realmente excluir?&quot;);"]) }}
                            <button type="submit" class="btn btn-danger btn-xs pull-left" style="margin-left: 1px"><span class="glyphicon glyphicon-trash"></span></button>
                            {{ Form::close() }}
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
@stop