<div class="form-group">
    {!! Form::label('name','Nome',['class'=>'control-label']) !!}
    {!! Form::text('name', null, ['class'=>'form-control','placeholder'=>'Nome']) !!}
</div>
{!! Form::submit('Gravar',['class'=>'btn btn-primary']) !!}