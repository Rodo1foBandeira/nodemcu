@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Entradas</h1>
        <a class="btn btn-primary btn-xs pull-right" href="{{route('input.create')}}">Cadastrar</a>
        <table id="table" class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Ip</th>
                <th>Nome node</th>
                <th>Nome entrada</th>
                <th>Grupo</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($inputs as $input)
                <tr>
                    <td>{{$input->id}}</td>
                    <td>{{$input->node->ip}}</td>
                    <td>{{$input->node->name}}</td>
                    <td>{{$input->name}}</td>
                    <td>{{$input->group->name}}</td>
                    <td>
                        <div class="form-inline">
                            <a class="btn btn-warning btn-xs" href="{{route('input.show', $input->id)}}"><span class="glyphicon glyphicon-eye-open"></span></a>
                            <a class="btn btn-warning btn-xs" href="{{route('input.edit', $input->id)}}"><span class="glyphicon glyphicon-pencil"></span></a>
                            {{ Form::open(['route' => ['input.destroy', $input->id], 'method' => 'delete', 'onsubmit' => "return confirm(&quot;Deseja realmente excluir?&quot;);"]) }}
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