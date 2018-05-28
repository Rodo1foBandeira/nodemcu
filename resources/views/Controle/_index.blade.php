<div id="groups" class="row">
    @foreach($groups as $group)
        <div class="card">
            <div class="card-header">{{ $group->name }}</div>
            <ul class="list-group list-group-flush">
                @foreach($group->ports as $port)
                    <li class="list-group-item">{{ $port->name }} &nbsp; <button onclick="controle.onOff('{{$port->id}})" class="btn btn-xs pull-right {{ $port->status == 0 ? 'btn-success' : 'btn-danger' }}">{{ $port->status == 0 ? 'Ligar' : 'Desligar' }}</button> </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>