<div id="groups" class="row">
    @foreach($groups as $group)
        <div class="card">
            <div class="card-header">{{ $group->name }}</div>
            <ul class="list-group list-group-flush">
                @foreach($group->ports as $port)
                    <li class="list-group-item">
                        {{ $port->name }} &nbsp
                        @if($port->type != "PWM_OUTPUT")
                            <button onclick="controle.onOff({{$port->id}})" class="btn btn-xs pull-right {{ $port->status == 0 ? 'btn-success' : 'btn-danger' }}">{{ $port->status == 0 ? 'Ligar' : 'Desligar' }}</button>
                        @else
                            <input type="range" data-id="{{$port->id}}" onchange="controle.ajustarPWM(this)" min="0" max="100" value="{{$port->status}}" class="slider">
                        @endif
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</div>