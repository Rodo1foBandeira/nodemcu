@extends('layouts.app')

@section('content')

    <div class="container">
        <h1>{{$input->name}}</h1>
        <div id="chartContainer" style="height: 370px; width:100%;"></div>
    </div>
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <script>
        var count = 0;
        var contInfinite = 0;
        var dataLength = 20;

        window.onload = function () {
            var dps = []; // dataPoints
            var chart = new CanvasJS.Chart("chartContainer", {
                title: {
                    text: "IRms"
                },
                axisY: {
                    includeZero: false
                },
                data: [{
                    type: "line",
                    dataPoints: dps
                }]
            });



            var updateChart = function (response) {
                contInfinite++;

                dps.push({
                    x: contInfinite,
                    y: response.Irms
                });

                count++;

                if (count > dataLength) {
                    dps.shift();
                    count = 0;
                }

                chart.render();
            };

            var get_valor = function () {
                $.get("getValor/{{$input->id}}", function (data) {
                    updateChart(data);
                })
            }

            setInterval(function(){get_valor()}, 1000);
        }

    </script>
@stop