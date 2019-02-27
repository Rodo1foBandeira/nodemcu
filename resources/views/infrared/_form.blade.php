<div class="form-group">
    {!! Form::label('port_id','Porta',['class'=>'control-label']) !!}
    {!! Form::select('port_id', $ports, null, ['class' => 'form-control']) !!}
</div>
<div class="form-group">
    {!! Form::label('name','Nome',['class'=>'control-label']) !!}
    {!! Form::text('name', null, ['class'=>'form-control','placeholder'=>'Nome']) !!}
</div>
<div class="form-group">
    {!! Form::label('lines','Linhas',['class'=>'control-label']) !!}
    {!! Form::number('lines', null, ['class'=>'form-control','placeholder'=>'Numero de linhas']) !!}
</div>
<div class="form-group">
    {!! Form::label('columns','Colunas',['class'=>'control-label']) !!}
    {!! Form::number('columns', null, ['class'=>'form-control','placeholder'=>'Numero de colunas']) !!}
</div>
{!! Form::label('buttons','Botões',['class'=>'control-label']) !!}
<a class="btn btn-primary btn-xs" style="margin-left: 3px;margin-bottom: 10px;" onclick="addButton()"><span class="glyphicon glyphicon-plus"></span></a>
<div class="form-inline" id="buttons">
    @if (isset($infrared) && isset($infrared->buttons))
        @foreach($infrared->buttons as $key => $button)

            <div class="form-inline" style="margin-bottom: 5px">
                {!! Form::text('buttons['.$key.'][id]', $button->id, ['style'=>'display:none;']) !!}
                &nbsp;Controle:&nbsp;
                {!! Form::select('buttons['.$key.'][device_id]', $devices, $button->code->device->id, ['class' => 'form-control', 'onchange' => 'getCodes(this, '.$key.')']) !!}
                &nbsp;Codigo:&nbsp;
                {!! Form::select('buttons['.$key.'][code_id]', \App\Code::where('device_id', $button->code->device->id)->pluck('name','id'), null, ['class' => 'form-control', 'id' => 'buttons_code_id'.$key]) !!}
                &nbsp;X:&nbsp;
                {!! Form::number('buttons['.$key.'][x]', null, ['class'=>'form-control','placeholder'=>'Posição']) !!}
                &nbsp;Y:&nbsp;
                {!! Form::number('buttons['.$key.'][y]', null, ['class'=>'form-control','placeholder'=>'Posição']) !!}
                <a class="btn btn-primary btn-xs" style="margin-left: 3px;" onclick="delRow(this)"><span class="glyphicon glyphicon-minus"></span></a>
            </div>
        @endforeach
    @endif
</div>
{!! Form::submit('Gravar',['class'=>'btn btn-primary']) !!}

<script>
    var devices = {!! json_encode($devices) !!};
    var key = -1;
    function addButton() {
        if (key == -1)
            key = $('#buttons').find('div').length;
        key++;
        var html = '&nbsp;Controle:&nbsp;'+
            '<select name="buttons['+key+'][device_id]" class="form-control" onchange="getCodes(this, '+key+')">';
        $.map(devices, function(v, k){
            html += '<option value="'+k+'">'+v+'</option>';
        });
        html += '</select>'+
            '&nbsp;Codigo:&nbsp;'+
            '<select name="buttons['+key+'][code_id]" id="buttons_code_id'+key+'" class="form-control"></select>'+
            '&nbsp;X:&nbsp;'+
            '<input placeholder="Posição" name="buttons['+key+'][x]" type="number" class="form-control">'+
            '&nbsp;Y:&nbsp;'+
            '<input placeholder="Posição" name="buttons['+key+'][y]" type="number" class="form-control">'+
            '<a onclick="delRow(this)" class="btn btn-primary btn-xs" style="margin-left: 3px;"><span class="glyphicon glyphicon-minus"></span></a>';
        var div = $('<div>',{
            class: "form-inline",
            style: "margin-bottom: 5px;",
            html: html
        });
        $('#buttons').append($(div));
        $($(div).find('select')[0]).change();
    }

    function delRow(element) {
        if (key == -1)
            key = $('#buttons').find('div').length;
        $(element).parent().remove();
    }

    function getCodes(element, key) {
        var device_id = $(element).val();
        $.get('http://'+window.location.host+'/device/getCodes/'+device_id, function (data) {
            $('#buttons_code_id'+key).empty();
            for (i=0; i<data.length; i++)
                $('#buttons_code_id'+key).append('<option value="'+data[i]['id']+'">'+data[i]['name']+'</option>');
        });
    }
</script>