@extends('layouts.app')

@section('content')

    <div class="container">
        @foreach($infrared->buttons as $key => $button)
            <button class="btn btn-primary" onclick="enviarCodigo({{$button->id}})">
                {{ $button->code->name }}
            </button>
        @endforeach
    </div>
@stop

<script>
    function enviarCodigo(button_id) {
        $.get('http://'+window.location.host+'/infrared/enviarCodigo/'+button_id);
    }
</script>