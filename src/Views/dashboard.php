<?= $this->include('julio101290\boilerplate\Views\load/daterangapicker') ?>
<!-- Extend from layout index -->
<?= $this->extend('julio101290\boilerplate\Views\layout\index') ?>

<!-- Section content -->
<?= $this->section('content') ?>
<div class="container-fluid">

    <div class="card card-default">
        <div class="card-header">

            <div class="float-left">
                <div class="btn-group">

                    <div id="reportrange" class="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                        <i class="fa fa-calendar"></i>&nbsp;
                        <span></span> <i class="fa fa-caret-down"></i>
                    </div>


                </div>

            </div>
        </div>

        <div class="card-body">
            <div class="row">
                <div class="col-lg-3 col-6">

                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3 class="totalEmitido">$ <?= $totalEmitido ?></h3>
                            <p>Facturas Emitidas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-bag"></i>
                        </div>
                        <a href="<?= base_url('admin/xml/desdeCaja/facturasEmitidas') ?>" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3 class="totalNomina">$ <?= $totalNomina ?></h3>
                            <p >Total Nomina</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-stats-bars"></i>
                        </div>
                        <a href="<?= base_url('admin/xml/desdeCaja/ingresosNomina') ?>" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3 class="gastosFacturados">$ <?= $totalGastos ?></h3>
                            <p>Gastos Facturados</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                        <a href="<?= base_url('admin/xml/desdeCaja/gastosFacturados') ?>" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">

                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3 class = "saldo">$ <?= $saldo ?></h3>
                            <p>Saldo</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-pie-graph"></i>
                        </div>
                        <a href="<?= base_url('admin/xml/') ?>" class="small-box-footer">Mas info <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

            </div>


            <div class="row">

                <div class="col-md-12">

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">Ingresos vs Egresos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="chart">
                                <div class="chartjs-size-monitor">
                                    <div class="chartjs-size-monitor-expand">
                                        <div class=""></div>
                                    </div>
                                    <div class="chartjs-size-monitor-shrink">
                                        <div class=""></div>
                                    </div>
                                </div>
                                <canvas id="areaChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 825px;" width="773" height="234" class="chartjs-render-monitor"></canvas>
                            </div>
                        </div>

                    </div>

                </div>


            </div>


            <!--  Row for pie chart -->

            <div class="row">

                <div class="col-md-6">

                    <div class="card card-primary">
                        <div class="card-header">
                            <h3 class="card-title">10 Productos más vendidos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body"><div class="chartjs-size-monitor"><div class="chartjs-size-monitor-expand"><div class=""></div></div><div class="chartjs-size-monitor-shrink"><div class=""></div></div></div>
                            <canvas id="donutChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 764px;" width="764" height="250" class="chartjs-render-monitor"></canvas>
                        </div>

                    </div>

                </div>

                <!-- Caja para los cartera pendiente -->


                <div class="col-md-6">

                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Cobranza pendiente </h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>

                        <div class="card-body p-0">
                            <ul class="products-list product-list-in-card pl-2 pr-2 carteraVencida">





                            </ul>
                        </div>

                        <div class="card-footer text-center">
                            <a href="admin/listSells/1990-01-01/2100-01-01/false/0/0/0" class="uppercase">Ver toda la cartera pendiente</a>
                        </div>

                    </div>

                </div>

            </div>


        </div>








    </div>




</div>
</div>

</div>
<?= $this->endSection() ?>



<?= $this->section('js') ?>




<script src=https://adminlte.io/themes/v3/plugins/chart.js/Chart.min.js"></script>


<script>

    var graficaPastel;
    var graficaBarras1;

    function graficasPastel(etiqueta, datos, color) {



        //-------------
        //- DONUT CHART -
        //-------------
        // Get context with jQuery - using jQuery's .get() method.


        //console.log(etiqueta);

        var donutChartCanvas = $('#donutChart').get(0).getContext('2d');




        var donutData = {
            labels: etiqueta,
            datasets: [
                {
                    data: datos,
                    backgroundColor: color,
                }
            ]
        }
        var donutOptions = {
            maintainAspectRatio: false,
            responsive: true,
        }
        //Create pie or douhnut chart
        // You can switch between pie and douhnut using the method below.
        graficaPastel = new Chart(donutChartCanvas, {
            type: 'doughnut',
            data: donutData,
            options: donutOptions
        })




    }


    function graficaBarras(label, data1, data2) {

        /* ChartJS
         * -------
         * Here we will create a few charts using ChartJS
         */

        var areaChartCanvas = $('#areaChart').get(0).getContext('2d')

        var areaChartData = {
            labels: label,
            datasets: [{
                    label: 'Ingresos',
                    backgroundColor: 'rgba(60,141,188,0.9)',
                    borderColor: 'rgba(60,141,188,0.8)',
                    pointRadius: true,
                    pointColor: '#3b8bba',
                    pointStrokeColor: 'rgba(60,141,188,1)',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(60,141,188,1)',
                    data: data1
                },
                {
                    label: 'Egresos',
                    backgroundColor: 'rgba(210, 214, 222, 1)',
                    borderColor: 'rgba(210, 214, 222, 1)',
                    pointRadius: true,
                    pointColor: 'rgba(210, 214, 222, 1)',
                    pointStrokeColor: '#c1c7d1',
                    pointHighlightFill: '#fff',
                    pointHighlightStroke: 'rgba(220,220,220,1)',
                    data: data2
                },
            ]
        }

        var areaChartOptions = {
            maintainAspectRatio: false,
            responsive: true,
            legend: {
                display: true
            },
            scales: {
                xAxes: [{
                        gridLines: {
                            display: true,
                        }
                    }],
                yAxes: [{
                        gridLines: {
                            display: true,
                        }
                    }]
            }
        }

        // This will get the first returned node in the jQuery collection.
        graficaBarras1 = new Chart(areaChartCanvas, {
            type: 'line',
            data: areaChartData,
            options: areaChartOptions
        })

    }

    $(function () {


        var desdeFecha = moment().subtract(365, 'days');
        var hastaFecha = moment();

        desdeFecha = desdeFecha.format('YYYY-MM-DD');
        hastaFecha = hastaFecha.format('YYYY-MM-DD');

        $.ajax({

            url: `<?= base_url('admin/graficas/') ?>` + desdeFecha + '/' + hastaFecha,
            method: "GET",
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {

                console.log(respuesta);

                $(".totalEmitido").html("$ " + respuesta["totalEmitido"]);
                $(".totalNomina").html("$ " + respuesta["xmlTotalNomina"]);
                $(".gastosFacturados").html("$ " + respuesta["xmlGastosFacturados"]);
                $(".saldo").html("$ " + respuesta["saldo"]);
                $(".carteraVencida").html(respuesta["carteraVencida"]);


                graficaBarras(respuesta["periodo"], respuesta["ingresos"], respuesta["egresos"]);
                graficasPastel(respuesta["nombreProducto"], respuesta["cantidadProducto"], respuesta["colorProducto"]);



            }

        })


    })






    $(function () {

        var start = moment().subtract(29, 'days');
        var end = moment();
        var todas = true;

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));



            var desdeFecha = start.format('YYYY-MM-DD');
            var hastaFecha = end.format('YYYY-MM-DD');

            //   graficaBarras(['Enero', 'February', 'March', 'April', 'May', 'June', 'July'], [28, 48, 40, 19, 86, 27, 90], [65, 59, 80, 81, 56, 55, 40]);

            graficaPastel.destroy();
            graficaBarras1.destroy();

            $.ajax({

                url: `<?= base_url('admin/graficas/') ?>` + desdeFecha + '/' + hastaFecha,
                method: "GET",
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
                success: function (respuesta) {

                    $(".totalEmitido").html("$ " + respuesta["totalEmitido"]);
                    $(".totalNomina").html("$ " + respuesta["xmlTotalNomina"]);
                    $(".gastosFacturados").html("$ " + respuesta["xmlGastosFacturados"]);
                    $(".saldo").html("$ " + respuesta["saldo"]);

                    graficaBarras(respuesta["periodo"], respuesta["ingresos"], respuesta["egresos"]);
                    graficasPastel(respuesta["nombreProducto"], respuesta["cantidadProducto"], respuesta["colorProducto"]);


                }

            })

        }

        $('#reportrange').daterangepicker({
            startDate: start,
            endDate: end,
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Ultimos 7 Dias': [moment().subtract(6, 'days'), moment()],
                'Ultimos 30 Dias': [moment().subtract(29, 'days'), moment()],
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],
                'Último Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Este Año': [moment().startOf('year'), moment().endOf('year')],
                'Todo': [moment().subtract(100, 'year').startOf('month'), moment().add(100, 'year').endOf('year')]
            }
        }, cb);

        //cb(start, end);





    });



</script>
<?= $this->endSection() ?>