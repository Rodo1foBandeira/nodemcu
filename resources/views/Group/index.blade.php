@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Grupos</h1>
        <a class="btn btn-primary btn-xs pull-right" href="{{route('group.create')}}">Cadastrar</a>
        <table id="table" class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($groups as $group)
                <tr>
                    <td>{{$group->id}}</td>
                    <td>{{$group->name}}</td>
                    <td>
                        <div class="form-inline">
                            <a class="btn btn-warning btn-xs" href="{{route('group.edit', $group->id)}}"><span class="glyphicon glyphicon-pencil"></span></a>
                            {{ Form::open(['route' => ['group.destroy', $group->id], 'method' => 'delete', 'onsubmit' => "return confirm(&quot;Deseja realmente excluir?&quot;);"]) }}
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