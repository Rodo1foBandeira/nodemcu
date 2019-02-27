@extends('layouts.app')

@section('content')
    <div class="container">
        <h1 class="text-center">Controles</h1>
        <a class="btn btn-primary btn-xs pull-right" href="{{route('device.create')}}">Cadastrar</a>
        <table id="table" class="table table-condensed table-striped">
            <thead>
            <tr>
                <th>#</th>
                <th>Nome</th>
                <th>Ações</th>
            </tr>
            </thead>
            <tbody>
            @foreach($devices as $device)
                <tr>
                    <td>{{$device->id}}</td>
                    <td>{{$device->name}}</td>
                    <td>
                        <div class="form-inline">
                            <a class="btn btn-warning btn-xs" href="{{route('device.edit', $device->id)}}"><span class="glyphicon glyphicon-pencil"></span></a>
                            {{ Form::open(['route' => ['device.destroy', $device->id], 'method' => 'delete', 'onsubmit' => "return confirm(&quot;Deseja realmente excluir?&quot;);"]) }}
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