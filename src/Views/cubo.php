<?= $this->include('julio101290\boilerplate\Views\load/daterangapicker') ?>
<?= $this->include('julio101290\boilerplate\Views\load\select2') ?>
<?= $this->include('julio101290\boilerplate\Views\load/toggle') ?>
<?= $this->include('julio101290\boilerplate\Views\load\select2') ?>
<?= $this->include('julio101290\boilerplate\Views\load\datatables') ?>

<?= $this->include('julio101290\boilerplateprojects\load/pivot') ?>
<?= $this->include('julio101290\boilerplate\Views\load\nestable') ?>
<!-- Extend from layout index -->
<?= $this->extend('julio101290\boilerplate\Views\layout\index') ?>

<!-- Section content -->
<?= $this->section('content') ?>



<!-- SELECT2 EXAMPLE -->
<div class="card card-default">
    <div class="card-header">

        <div class="float-left">
            <div class="btn-group">

                <div id="reportrange" style="background: #fff; cursor: pointer; padding: 5px 10px; border: 1px solid #ccc; width: 100%">
                    <i class="fa fa-calendar"></i>&nbsp;
                    <span></span> <i class="fa fa-caret-down"></i>
                </div>


            </div>

            <div class="btn-group">



                <div class="form-group">
                    <label for="idEmpresaFiltro">Empresa </label>
                    <select id='idEmpresaFiltro' name='idEmpresaFiltro' class="idEmpresaFiltro" style='width: 80%;'>

                        <?php
                        if (isset($idEmpresa)) {

                            echo "   <option value='$idEmpresa'>$idEmpresa - $nombreEmpresa</option>";
                        } else {

                            echo "  <option value='0'>Todas las empresas</option>";

                            foreach ($empresas as $key => $value) {

                                echo "<option value='$value[id]'>$value[id] - $value[nombre] </option>  ";
                            }
                        }
                        ?>

                    </select>
                </div>

            </div>


            <div class="btn-group">



                <div class="form-group">
                    <label for="idPRoyectoFiltro">Proyecto </label>
                    <select id='idPRoyectoFiltro' name='idPRoyectoFiltro' class="idPRoyectoFiltro" style='width: 100%;'>

                        <?php
                        echo "  <option value='0'>Todas los proyecto o seleccione un proyecto a filtrar</option>";

                        /*
                          if (isset($idSucursal)) {

                          echo "   <option value='$idSucursal'>$idSucursal - $nombreSucursal</option>";
                          }
                         * 
                         * */
                        ?>

                    </select>
                </div>

            </div>




            <div class="btn-group">



                <div class="form-group">
                    <label for="productos">Responsable </label>
                    <select id='idUsuarioFiltro' name='idUsuarioFiltro' class="idUsuarioFiltro" style='width: 100%;'>

                        <?php
                        echo "  <option value='0'>Todas los responsables</option>";
                        ?>

                    </select>
                </div>

            </div>

            <div class="btn-group" hidden>



                <input type="checkbox" id="chkTodasLasActividades" name="chkTodasLasActividades" class="chkTodasLasActividades" data-width="250" data-height="40" checked data-toggle="toggle" data-on="Pendientes" data-off="Todos los pendientes" data-onstyle="danger" data-offstyle="success">

            </div>


        </div>


        <div class="float-right">
            <div class="btn-group">

                <button class="btn btn-primary btnGenerarCubo" ><i class="fa fa-plus"></i>

                    Generar Cubo

                </button>

            </div>
        </div>
    </div>

    <div class="card-body">
        <div class="row">
            <div class="col-md-12">

                <div id="cubo" name="cubo" style="margin: 30px;"></div>

            </div>
        </div>
    </div>
</div>
<!-- /.card -->

<?= $this->endSection() ?>


<?= $this->section('js') ?>
<script>

    $(".btnGenerarCubo").on("click", function () {


        console.log("asd");

        var datePicker = $('#reportrange').data('daterangepicker');
        var desdeFecha = datePicker.startDate.format('YYYY-MM-DD');
        var hastaFecha = datePicker.endDate.format('YYYY-MM-DD');
        var idEmpresa = $("#idEmpresaFiltro").val();
        var idPRoyectoFiltro = $("#idPRoyectoFiltro").val();
        var idUsuarioFiltro = $("#idUsuarioFiltro").val();


        if ($(this).is(':checked')) {

            var todas = true;

        } else {

            var todas = false;

        }
        /*
         $("#cubo").pivotUI(
         [
         {color: "blue", shape: "circle"},
         {color: "red", shape: "triangle"}
         ],
         {
         rows: ["color"],
         cols: ["shape"]
         }
         );
         
         */
        $.ajax({

            url: `<?= base_url('admin/actividadesCubo') ?>/` + desdeFecha + '/' + hastaFecha + '/' + todas + '/' + idEmpresa + '/' + idPRoyectoFiltro + '/' + idUsuarioFiltro,
            method: "GET",
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
     
                

                $("#cubo").pivotUI(
                        
                        respuesta
                        );


            }

        })

    });



    $(function () {

        var start = moment().subtract(100000, 'days');
        var end = moment();
        var todas = true;

        function cb(start, end) {
            $('#reportrange span').html(start.format('MMMM D, YYYY') + ' - ' + end.format('MMMM D, YYYY'));

            if ($('#chkTodasLasActividades').is(':checked')) {

                todas = true;

            } else {

                todas = false;

            }



            var desdeFecha = start.format('YYYY-MM-DD');
            var hastaFecha = end.format('YYYY-MM-DD');
            var idEmpresa = $("#idEmpresaFiltro").val();
            var idPRoyectoFiltro = $("#idPRoyectoFiltro").val();
            var idUsuarioFiltro = $("#idUsuarioFiltro").val();

            tableActividades.ajax.url(`<?= base_url('admin/actividades') ?>/` + desdeFecha + '/' + hastaFecha + '/' + todas + '/' + idEmpresa + '/' + idPRoyectoFiltro + '/' + idUsuarioFiltro).load();


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
                'Todo': [moment().subtract(100, 'year').startOf('month'), moment().add(100, 'year').endOf('year')]
            }
        }, cb);

        cb(start, end);





    });


    $("#idEmpresaFiltro").select2();

    $("#idPRoyectoFiltro").select2({
        ajax: {
            url: "<?= site_url('admin/proyecto/getProyectosAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresaFiltro').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idEmpresa: idEmpresa // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);
                return {
                    results: response.data
                };
            },
            cache: true
        }
    });



    // Obtenemos los usuarios
    $("#idUsuarioFiltro").select2({
        ajax: {
            url: "<?= site_url('admin/usuarios/getUsuariosEmpresaAjaxSelect2') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresaFiltro').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idEmpresa: idEmpresa // search term
                };
            },
            processResults: function (response) {

                // Update CSRF Token
                $('.txt_csrfname').val(response.token);

                return {
                    results: response.data
                };
            },
            cache: true
        }
    });

</script>
<?= $this->endSection() ?>
        