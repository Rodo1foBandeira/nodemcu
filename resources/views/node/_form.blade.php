<div class="form-group">
    {!! Form::label('ip','Ip',['class'=>'control-label']) !!}
    {!! Form::text('ip', null, ['class'=>'form-control','placeholder'=>'Ip']) !!}
</div>
<div class="form-group">
    {!! Form::label('name','Nome',['class'=>'control-label']) !!}
    {!! Form::text('name', null, ['class'=>'form-control','placeholder'=>'Nome']) !!}
</div>

{!! Form::label('ports','Portas',['class'=>'control-label']) !!}
<a class="btn btn-primary btn-xs" style="margin-left: 3px;margin-bottom: 10px;" onclick="addPort()"><span class="glyphicon glyphicon-plus"></span></a>
<div class="form-inline" id="ports">
    @if (isset($node) && isset($node->ports))
        @foreach($node->ports as $key => $port)

            <div class="form-inline" style="margin-bottom: 5px">
                {!! Form::text('ports['.$key.'][id]', $port->id, ['style'=>'display:none;']) !!}
                &nbsp;Grupo:&nbsp;
                {!! Form::select('ports['.$key.'][group_id]', $groups, null, ['class' => 'form-control']) !!}
                &nbsp;Pino:&nbsp;
                {!! Form::text('ports['.$key.'][pin]', $port->pin, ['class'=>'form-control','placeholder'=>'Pino', 'maxlegth'=>'2']) !!}
                &nbsp;Nome:&nbsp;
                {!! Form::text('ports['.$key.'][name]', $port->name, ['class'=>'form-control','placeholder'=>'Nome']) !!}
                &nbsp;Tipo:&nbsp;
                {!! Form::select('ports['.$key.'][type]', [null => 'Selecione'] + ['DIGITAL_OUTPUT' => 'DIGITAL_OUTPUT','DIGITAL_INPUT'=>'DIGITAL_INPUT','ANALOG_INPUT'=>'ANALOG_INPUT','ANALOG_OUTPUT'=>'ANALOG_OUTPUT', 'PWM_OUTPUT'=>'PWM_OUTPUT', 'INFRARED_OUTPUT'=>'INFRARED_OUTPUT', 'INFRARED_INPUT'=>'INFRARED_INPUT'], $port->type, ['class' => 'form-control']) !!}
                <a class="btn btn-primary btn-xs" style="margin-left: 3px;" onclick="delRow(this)"><span class="glyphicon glyphicon-minus"></span></a>
            </div>
        @endforeach
    @endif
</div>

{!! Form::submit('Gravar',['class'=>'btn btn-primary']) !!}

<script>
    var groups = {!! json_encode($groups) !!};
    var key = -1;
    function addPort() {
        if (key == -1)
            key = $('#ports').find('div').length;
        key++;
        var html = '&nbsp;Grupo:&nbsp;'+
            '<select name="ports['+key+'][group_id]" class="form-control">';
        $.map(groups, function(v, k){
            html += '<option value="'+k+'">'+v+'</option>';
        });
        html += '</select>'+
                '&nbsp;Pino:&nbsp;'+
            '<input placeholder="Pino" maxlegth="2" name="ports['+key+'][pin]" type="text" class="form-control">'+
                    '&nbsp;Nome:&nbsp;'+
            '<input placeholder="Nome" name="ports['+key+'][name]" type="text" class="form-control">'+
                    '&nbsp;Tipo:&nbsp;'+
            '<select name="ports['+key+'][type]" class="form-control"><option value="">Selecione</option><option value="DIGITAL_OUTPUT">DIGITAL_OUTPUT</option><option value="DIGITAL_INPUT">DIGITAL_INPUT</option><option value="ANALOG_INPUT">ANALOG_INPUT</option><option value="ANALOG_OUTPUT">ANALOG_OUTPUT</option><option value="PWM_OUTPUT">PWM_OUTPUT</option><option value="INFRARED_OUTPUT">INFRARED_OUTPUT</option><option value="INFRARED_INPUT">INFRARED_INPUT</option></select>'+
            '<a onclick="delRow(this)" class="btn btn-primary btn-xs" style="margin-left: 3px;"><span class="glyphicon glyphicon-minus"></span></a>';

        var div = $('<div>',{
            class: "form-inline",
            style: "margin-bottom: 5px;",
            html: html
        });
        $('#ports').append($(div));
    }

    function delRow(element) {
        if (key == -1)
            key = $('#ports').find('div').length;
        $(element).parent().remove();
    }
</script>