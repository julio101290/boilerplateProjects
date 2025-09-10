<?= $this->include('julio101290\boilerplate\Views\load/daterangapicker') ?>
<?= $this->include('julio101290\boilerplate\Views\load/toggle') ?>
<?= $this->include('julio101290\boilerplate\Views\load\select2') ?>
<?= $this->include('julio101290\boilerplate\Views\load\datatables') ?>
<?= $this->include('julio101290\boilerplate\Views\load/extrasDatatable') ?>
<?= $this->include('julio101290\boilerplate\Views\load\nestable') ?>
<!-- Extend from layout index -->
<?= $this->extend('julio101290\boilerplate\Views\layout\index') ?>

<!-- Section content -->
<?= $this->section('content') ?>

<?= $this->include('julio101290\boilerplateprojects\Views/modulesActividades/modalCaptureActividades') ?>
<?= $this->include('julio101290\boilerplateprojects\Views/modulesActividades/modalAvances') ?>
<?= $this->include('julio101290\boilerplateprojects\Views/modulesActividades/ListaAvances') ?>

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

            <div class="btn-group">



                <input type="checkbox" id="chkTodasLasActividades" name="chkTodasLasActividades" class="chkTodasLasActividades" data-width="250" data-height="40" checked data-toggle="toggle" data-on="Pendientes" data-off="Todos los pendientes" data-onstyle="danger" data-offstyle="success">

            </div>


        </div>


        <div class="float-right">
            <div class="btn-group">

                <button class="btn btn-primary btnAddActividades" data-toggle="modal" data-target="#modalAddActividades"><i class="fa fa-plus"></i>

                    <?= lang('actividades.add') ?>

                </button>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="tableActividades" class="table table-striped table-hover va-middle tableActividades">
                        <thead>
                            <tr>

                                <th>#</th>
                                <th><?= lang('actividades.fields.idEmpresa') ?></th>
                                <th><?= lang('actividades.fields.idProyecto') ?></th>
                                <th><?= lang('actividades.fields.etapa') ?></th>
                                <th><?= lang('actividades.fields.concepto') ?></th>
                                <th><?= lang('actividades.fields.descripcion') ?></th>
                                <th><?= lang('actividades.fields.fechaInicio') ?></th>
                                <th><?= lang('actividades.fields.fechaFinal') ?></th>
                                <th><?= lang('actividades.fields.cantEstimada') ?></th>
                                <th><?= lang('actividades.fields.cantReal') ?></th>
                                <th><?= lang('actividades.fields.unidadMedida') ?></th>
                                <th><?= lang('actividades.fields.status') ?></th>
                                <th><?= lang('actividades.fields.proveedor') ?></th>
                                <th><?= lang('actividades.fields.modalidadActividad') ?></th>
                                <th>% Avanzado</th>
                                <th><?= lang('actividades.fields.usuario') ?></th>
                                <th><?= lang('actividades.fields.created_at') ?></th>
                                <th><?= lang('actividades.fields.updated_at') ?></th>
                                <th><?= lang('actividades.fields.deleted_at') ?></th>

                                <th><?= lang('actividades.fields.actions') ?> </th>

                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- /.card -->

<?= $this->endSection() ?>


<?= $this->section('js') ?>
<script>

    /**
     * Cargamos la tabla
     */

    var tableActividades = $('#tableActividades').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print', 'pageLength'],
        lengthMenu: [
            [150, 200, 500, -1],
            ['150 renglones', '200 renglones', '500 renglones', 'Todo']
        ],
        order: [[1, 'asc']],

        ajax: {
            url: '<?= base_url('admin/actividades') ?>',
            method: 'GET',
            dataType: "json"
        },
        columnDefs: [{
                orderable: false,
                targets: [19],
                searchable: false,
                targets: [19]

            }],

        columns: [{
                'data': 'id'
            },

            {
                'data': 'nombreEmpresa'
            },

            {
                'data': 'nombreProyecto'
            },

            {
                'data': 'nombreEtapa'
            },

            {
                'data': 'nombreConcepto'
            },

            {
                'data': 'descripcion'
            },

            {
                'data': 'fechaInicio'
            },

            {
                'data': 'fechaFinal'
            },

            {
                'data': 'cantEstimada'
            },

            {
                'data': 'cantReal'
            },

            {
                'data': 'nombreUnidadMedida'
            },

            {
                'data': 'descripcionStatus'
            },

            {
                'data': 'nombreProveedor'
            },

            {
                'data': 'modalidadActividad'
            },

            {
                'data': 'porcAvanzado'
            },

            {
                'data': 'username'
            },

            {
                'data': 'created_at'
            },

            {
                'data': 'updated_at'
            },

            {
                'data': 'deleted_at'
            },

            {
                "data": function (data) {

                    var botonAvance = "";


                    if (data.porcAvanzado == 100) {

                        botonAvance = `<button class="btn btn-success disabled btnSetPayment" data-toggle="modal" balance ="${data.balance}" uuid="${data.UUID}" folio="${data.folio}" data-toggle="modal" data-target="#modalPayment"  >  <i class="fab fa-cc-visa"></i></button> `;

                    } else {

                        botonAvance = `<button class="btn btn-success btnSetAvance" data-toggle="modal"  idActividad="${data.id}" data-toggle="modal" data-target="#modalAvance"  >  <i class="fa fa-battery-half"></i></button> `;

                    }


                    return `<td class="text-right py-0 align-middle">
                         <div class="btn-group btn-group-sm">
                             ${botonAvance}
                             <button class="btn bg-maroon btnListaAvances" data-toggle="modal"  idActividad="${data.id}" data-toggle="modal" data-target="#modalListaAvances"  >  <i class="fas fa-search"></i></button>
                             <button class="btn btn-warning btnEditActividades" data-toggle="modal" idActividades="${data.id}" data-target="#modalAddActividades">  <i class=" fa fa-edit"></i></button>
                             <button class="btn btn-danger btn-delete" data-id="${data.id}"><i class="fas fa-trash"></i></button>
                         </div>
                         </td>`
                }
            }
        ]
    });




    /*=============================================
     Carga lista de avance
     =============================================*/

    $(".tableActividades").on("click", '.btnListaAvances', function () {


        var idActividad = $(this).attr("idActividad");

        tablaAvances.ajax.url(`<?= base_url('admin/avanceActividad/getAvances') ?>/` + idActividad).load();

    });

    $(document).on('click', '#btnSaveActividades', function (e) {


        var idActividades = $("#idActividades").val();
        var idEmpresa = $("#idEmpresa").val();
        var idProyecto = $("#idProyecto").val();
        var etapa = $("#etapa").val();
        var concepto = $("#concepto").val();
        var descripcion = $("#descripcion").val();
        var fechaInicio = $("#fechaInicio").val();
        var fechaFinal = $("#fechaFinal").val();
        var cantEstimada = $("#cantEstimada").val();
        var cantReal = $("#cantReal").val();
        var unidadMedida = $("#unidadMedida").val();
        var status = $("#status").val();
        var idUsuario = $("#idUsuario").val();
        var idProveedor = $("#idProveedor").val();
        var modalidadActividad = $("#modalidadActividad").val();

        var costoUnitario = $("#costoUnitario").val();
        var costoTotalEstimado = $("#costoTotalEstimado").val();
        var costoTotalReal = $("#costoTotalReal").val();
        var producto = $("#producto").val();


        $("#btnSaveActividades").attr("disabled", true);

        var datos = new FormData();
        datos.append("idActividades", idActividades);
        datos.append("idEmpresa", idEmpresa);
        datos.append("idProyecto", idProyecto);
        datos.append("etapa", etapa);
        datos.append("concepto", concepto);
        datos.append("descripcion", descripcion);
        datos.append("fechaInicio", fechaInicio);
        datos.append("fechaFinal", fechaFinal);
        datos.append("cantEstimada", cantEstimada);
        datos.append("cantReal", cantReal);
        datos.append("unidadMedida", unidadMedida);
        datos.append("status", status);
        datos.append("idUsuario", idUsuario);

        datos.append("idProveedor", idProveedor);
        datos.append("modalidadActividad", modalidadActividad);

        datos.append("costoUnitario", costoUnitario);
        datos.append("costoTotalEstimado", costoTotalEstimado);
        datos.append("costoTotalReal", costoTotalReal);
        datos.append("producto", producto);

        $.ajax({

            url: "<?= base_url('admin/actividades/save') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            success: function (respuesta) {
                if (respuesta.match(/Correctamente.*/)) {

                    Toast.fire({
                        icon: 'success',
                        title: "Guardado Correctamente"
                    });

                    tableActividades.ajax.reload();
                    $("#btnSaveActividades").removeAttr("disabled");


                    $('#modalAddActividades').modal('hide');
                } else {

                    Toast.fire({
                        icon: 'error',
                        title: respuesta
                    });

                    $("#btnSaveActividades").removeAttr("disabled");


                }

            }

        }

        ).fail(function (jqXHR, textStatus, errorThrown) {

            if (jqXHR.status === 0) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "No hay conexión.!" + jqXHR.responseText
                });

                $("#btnSaveActividades").removeAttr("disabled");


            } else if (jqXHR.status == 404) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested page not found [404]" + jqXHR.responseText
                });

                $("#btnSaveActividades").removeAttr("disabled");

            } else if (jqXHR.status == 500) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Internal Server Error [500]." + jqXHR.responseText
                });


                $("#btnSaveActividades").removeAttr("disabled");

            } else if (textStatus === 'parsererror') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested JSON parse failed." + jqXHR.responseText
                });

                $("#btnSaveActividades").removeAttr("disabled");

            } else if (textStatus === 'timeout') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Time out error." + jqXHR.responseText
                });

                $("#btnSaveActividades").removeAttr("disabled");

            } else if (textStatus === 'abort') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ajax request aborted." + jqXHR.responseText
                });

                $("#btnSaveActividades").removeAttr("disabled");

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: 'Uncaught Error: ' + jqXHR.responseText
                });


                $("#btnSaveActividades").removeAttr("disabled");

            }
        })

    });



    /**
     * Carga datos actualizar
     */


    /*=============================================
     EDITAR Actividades
     =============================================*/
    $(".tableActividades").on("click", ".btnEditActividades", function () {

        var idActividades = $(this).attr("idActividades");

        var datos = new FormData();
        datos.append("idActividades", idActividades);

        $.ajax({

            url: "<?= base_url('admin/actividades/getActividades') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
                $("#idActividades").val(respuesta["id"]);

                $("#idEmpresa").val(respuesta["idEmpresa"]).trigger("change");


                $("#idProyecto").empty();
                var newOptionProyecto = new Option(respuesta["nombreProyecto"], respuesta["idProyecto"], true, true);
                $('#idProyecto').append(newOptionProyecto).trigger('change');
                $("#idProyecto").val(respuesta["idProyecto"]).trigger('change');


                $("#etapa").empty();
                var newOptionEtapa = new Option(respuesta["nombreEtapa"], respuesta["etapa"], true, true);
                $('#etapa').append(newOptionEtapa).trigger('change');
                $("#etapa").val(respuesta["etapa"]).trigger('change');

                $("#concepto").empty();
                var newOptionConcepto = new Option(respuesta["nombreConcepto"], respuesta["concepto"], true, true);
                $('#concepto').append(newOptionConcepto).trigger('change');
                $("#concepto").val(respuesta["concepto"]).trigger('change');

                $("#unidadMedida").empty();
                var newOptionUnidadMedida = new Option(respuesta["nombreUnidadMedida"], respuesta["unidadMedida"], true, true);
                $('#unidadMedida').append(newOptionUnidadMedida).trigger('change');
                $("#unidadMedida").val(respuesta["unidadMedida"]).trigger('change');

                $("#idUsuario").empty();
                var newOptionIdUsuario = new Option(respuesta["nombreUsuario"], respuesta["idUsuario"], true, true);
                $('#idUsuario').append(newOptionIdUsuario).trigger('change');
                $("#idUsuario").val(respuesta["idUsuario"]).trigger('change');


                $("#idProveedor").empty();
                var newOptionIdUProveedor = new Option(respuesta["nombreProveedor"], respuesta["idProveedor"], true, true);
                $('#idProveedor').append(newOptionIdUProveedor).trigger('change');
                $("#idProveedor").val(respuesta["idProveedor"]).trigger('change');

                $("#idProducto").empty();
                var newOptionProducto = new Option(respuesta["nombreProducto"], respuesta["producto"], true, true);
                $('#idProducto').append(newOptionProducto).trigger('change');
                $("#idProducto").val(respuesta["producto"]).trigger('change');


                $("#modalidadActividad").val(respuesta["modalidadActividad"]);

                $("#descripcion").val(respuesta["descripcion"]);
                $("#fechaInicio").val(respuesta["fechaInicio"]);
                $("#fechaFinal").val(respuesta["fechaFinal"]);
                $("#cantEstimada").val(respuesta["cantEstimada"]);
                $("#cantReal").val(respuesta["cantReal"]);
                $("#unidadMedida").val(respuesta["unidadMedida"]);
                $("#status").val(respuesta["status"]);

                $("#costoUnitario").val(respuesta["costoUnitario"]);
                $("#costoTotalEstimado").val(respuesta["costoTotalEstimado"]);
                $("#costoTotalReal").val(respuesta["costoTotalReal"]);




            }

        })

    })


    /*=============================================
     ELIMINAR actividades
     =============================================*/
    $(".tableActividades").on("click", ".btn-delete", function () {

        var idActividades = $(this).attr("data-id");

        Swal.fire({
            title: '<?= lang('boilerplate.global.sweet.title') ?>',
            text: "<?= lang('boilerplate.global.sweet.text') ?>",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: '<?= lang('boilerplate.global.sweet.confirm_delete') ?>'
        })
                .then((result) => {
                    if (result.value) {
                        $.ajax({
                            url: `<?= base_url('admin/actividades') ?>/` + idActividades,
                            method: 'DELETE',
                        }).done((data, textStatus, jqXHR) => {
                            Toast.fire({
                                icon: 'success',
                                title: jqXHR.statusText,
                            });


                            tableActividades.ajax.reload();
                        }).fail((error) => {
                            Toast.fire({
                                icon: 'error',
                                title: error.responseJSON.messages.error,
                            });
                        })
                    }
                })
    })

    $(function () {
        $("#modalAddActividades").draggable();

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




    /**@abstract
     * 
     * Al cambiar la el rango de fecha
     */

    $("#chkTodasLasActividades").on("change", function () {

        var datePicker = $('#reportrange').data('daterangepicker');
        var desdeFecha = datePicker.startDate.format('YYYY-MM-DD');
        var hastaFecha = datePicker.endDate.format('YYYY-MM-DD');
        var idEmpresa = $("#idEmpresaFiltro").val();
        var idProyectoFiltro = $("#idPRoyectoFiltro").val();
        var idUsuarioFiltro = $("#idUsuarioFiltro").val();


        var todas = $(this).is(':checked') ? 1 : 0

        tableActividades.ajax.url(`<?= base_url('admin/actividades') ?>/` + desdeFecha + '/' + hastaFecha + '/' + todas + '/' + idEmpresa + '/' + idProyectoFiltro + '/' + idUsuarioFiltro).load();

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

<?php
if (isset($actividadesVencidas)) {



    $filtrosactividadesVencidas = <<<EOT

                            todas = false;
                            var desdeFecha = moment().subtract(100, 'years'); 
                            var hastaFecha = moment();
                            var idEmpresa = $("#idEmpresaFiltro").val();
                            var idPRoyectoFiltro = $("#idPRoyectoFiltro").val();
                            var idUsuarioFiltro = $("#idUsuarioFiltro").val();
                
                            tableActividades.ajax.url(`<?= base_url('admin/actividades') ?>/` + desdeFecha + '/' + hastaFecha + '/' + todas + '/' + idEmpresa + '/' + idPRoyectoFiltro + '/' + idUsuarioFiltro).load();

                            EOT;

    echo $filtrosactividadesVencidas;
}

if (isset($actividadesPorVencer)) {



    $filtrosActividadesPorVencer = <<<EOT

                            todas = false;
                            var desdeFecha = moment().subtract(100, 'years'); 
                            var hastaFecha = moment().subtract(2, 'days');
                            var idEmpresa = $("#idEmpresaFiltro").val();
                            var idPRoyectoFiltro = $("#idPRoyectoFiltro").val();
                            var idUsuarioFiltro = $("#idUsuarioFiltro").val();
                
                            tableActividades.ajax.url(`<?= base_url('admin/actividades') ?>/` + desdeFecha + '/' + hastaFecha + '/' + todas + '/' + idEmpresa + '/' + idPRoyectoFiltro + '/' + idUsuarioFiltro).load();

                            EOT;

    echo $filtrosActividadesPorVencer;
}


if (isset($actividadesPendiente)) {



    $filtrosActividadesPendientes = <<<EOT

                            todas = false;
                            var desdeFecha = moment().subtract(100, 'years'); 
                            var hastaFecha = moment().add(100, 'years'); ;
                            var idEmpresa = $("#idEmpresaFiltro").val();
                            var idPRoyectoFiltro = $("#idPRoyectoFiltro").val();
                            var idUsuarioFiltro = $("#idUsuarioFiltro").val();
                
                            tableActividades.ajax.url(`<?= base_url('admin/actividades') ?>/` + desdeFecha + '/' + hastaFecha + '/' + todas + '/' + idEmpresa + '/' + idPRoyectoFiltro + '/' + idUsuarioFiltro).load();

                            EOT;

    echo $filtrosActividadesPendientes;
}




if (isset($actividadesFinalizadas)) {



    $filtrosActividadesFinalizadas = <<<EOT

                            todas = true;
                            var desdeFecha = moment().subtract(100, 'years'); 
                            var hastaFecha = moment().add(100, 'years'); ;
                            var idEmpresa = $("#idEmpresaFiltro").val();
                            var idPRoyectoFiltro = $("#idPRoyectoFiltro").val();
                            var idUsuarioFiltro = $("#idUsuarioFiltro").val();
                
                            tableActividades.ajax.url(`<?= base_url('admin/actividades') ?>/` + desdeFecha + '/' + hastaFecha + '/' + todas + '/' + idEmpresa + '/' + idPRoyectoFiltro + '/' + idUsuarioFiltro).load();
                            $("#chkTodasLasActividades").bootstrapToggle("off"); 
                            EOT;

    echo $filtrosActividadesFinalizadas;
}
?>



</script>
<?= $this->endSection() ?>
        