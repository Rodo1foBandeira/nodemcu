<div class="form-group">
    {!! Form::label('name','Nome',['class'=>'control-label']) !!}
    {!! Form::text('name', null, ['class'=>'form-control','placeholder'=>'Nome']) !!}
</div>
<div class="form-group">
    {!! Form::label('type','Tipo',['class'=>'control-label']) !!}
    {!! Form::text('type', null, ['class'=>'form-control','placeholder'=>'Tipo']) !!}
</div>
<div class="form-group">
    {!! Form::label('bits','Bits',['class'=>'control-label']) !!}
    {!! Form::number('bits', null, ['class'=>'form-control','placeholder'=>'32']) !!}
</div>
{!! Form::label('codes','Codigos',['class'=>'control-label']) !!}
<a class="btn btn-primary btn-xs" style="margin-left: 3px;margin-bottom: 10px;" onclick="addCode()"><span class="glyphicon glyphicon-plus"></span></a>
<div class="form-inline" id="codes">
    @if (isset($device) && isset($device->codes))
        @foreach($device->codes as $key => $code)

            <div class="form-inline" style="margin-bottom: 5px">
                {!! Form::text('codes['.$key.'][id]', $code->id, ['style'=>'display:none;']) !!}
                &nbsp;Nome:&nbsp;
                {!! Form::text('codes['.$key.'][name]', $code->name, ['class'=>'form-control','placeholder'=>'Nome']) !!}
                &nbsp;Codigo:&nbsp;
                {!! Form::text('codes['.$key.'][code]', $code->code, ['class'=>'form-control','placeholder'=>'Codigo']) !!}
                <a class="btn btn-primary btn-xs" style="margin-left: 3px;" onclick="delRow(this)"><span class="glyphicon glyphicon-minus"></span></a>
            </div>
        @endforeach
    @endif
</div>
<div class="form-group">
    {!! Form::label('ports','Portas',['class'=>'control-label']) !!}
    <div class="form-inline">
        {!! Form::select('ports', $ports, null, ['class' => 'form-control']) !!}
        <a class="btn btn-primary btn-xs" style="margin-left: 3px;margin-bottom: 10px;" onclick="lerCodigo()"><span class="glyphicon glyphicon-eye-open"></span></a>
    </div>
    <textarea class="form-control" id="retornoIR" rows="4"></textarea>
</div>
{!! Form::submit('Gravar',['class'=>'btn btn-primary']) !!}

<script>
    var key = -1;
    function addCode() {
        if (key == -1)
            key = $('#codes').find('div').length;
        key++;
        var html = '&nbsp;Nome:&nbsp;'+
            '<input placeholder="Nome" name="codes['+key+'][name]" type="text" class="form-control">'+
            '&nbsp;Codigo:&nbsp;'+
            '<input placeholder="Codigo" name="codes['+key+'][code]" type="text" class="form-control">'+
            '<a onclick="delRow(this)" class="btn btn-primary btn-xs" style="margin-left: 3px;"><span class="glyphicon glyphicon-minus"></span></a>';
        var div = $('<div>',{
            class: "form-inline",
            style: "margin-bottom: 5px;",
            html: html
        });
        $('#codes').append($(div));
    }

    function delRow(element) {
        if (key == -1)
            key = $('#codes').find('div').length;
        $(element).parent().remove();
    }

    function lerCodigo () {
        var port_id = $('#ports').val();
        $.get(window.location.href+'/lerCodigo/'+port_id, function (data) {
            $('#retornoIR').text(data);
        });
    }
</script>