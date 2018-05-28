@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Nodes</h1>
        <a class="btn btn-primary btn-xs pull-right" href="{{route('node.create')}}">Cadastrar</a>
        <table id="table" class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Ip</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($nodes as $node)
                <tr>
                    <td>{{$node->id}}</td>
                    <td>{{$node->ip}}</td>
                    <td>{{$node->name}}</td>
                    <td>
                        <div class="form-inline">
                            <a class="btn btn-warning btn-xs" href="{{route('node.edit', $node->id)}}"><span class="glyphicon glyphicon-pencil"></span></a>
                            {{ Form::open(['route' => ['node.destroy', $node->id], 'method' => 'delete', 'onsubmit' => "return confirm(&quot;Deseja realmente excluir?&quot;);"]) }}
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