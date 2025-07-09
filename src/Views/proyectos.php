<?= $this->include('julio101290\boilerplate\Views\load\select2') ?>
<?= $this->include('julio101290\boilerplate\Views\load\datatables') ?>
<?= $this->include('julio101290\boilerplate\Views\load\nestable') ?>
<!-- Extend from layout index -->
<?= $this->extend('julio101290\boilerplate\Views\layout\index') ?>

<!-- Section content -->
<?= $this->section('content') ?>

<?= $this->include('julio101290\boilerplateprojects\Views/modulesProyectos/modalCaptureProyectos') ?>

<!-- SELECT2 EXAMPLE -->
<div class="card card-default">
    <div class="card-header">
        <div class="float-right">
            <div class="btn-group">

                <button class="btn btn-primary btnAddProyectos" data-toggle="modal" data-target="#modalAddProyectos"><i class="fa fa-plus"></i>

                    <?= lang('proyectos.add') ?>

                </button>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="tableProyectos" class="table table-striped table-hover va-middle tableProyectos">
                        <thead>
                            <tr>

                                <th>#</th>
                                <th><?= lang('proyectos.fields.idEmpresa') ?></th>
                                <th><?= lang('proyectos.fields.idSucursal') ?></th>
                                <th><?= lang('proyectos.fields.tipoProyecto') ?></th>
                                <th><?= lang('proyectos.fields.descripcion') ?></th>
                                <th><?= lang('proyectos.fields.fechaInicio') ?></th>
                                <th><?= lang('proyectos.fields.fechaFinal') ?></th>
                                <th><?= lang('proyectos.fields.idCliente') ?></th>
                                <th><?= lang('proyectos.fields.responsable') ?></th>
                                <th><?= lang('proyectos.fields.created_at') ?></th>
                                <th><?= lang('proyectos.fields.updated_at') ?></th>
                                <th><?= lang('proyectos.fields.deleted_at') ?></th>

                                <th><?= lang('proyectos.fields.actions') ?> </th>

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

    var tableProyectos = $('#tableProyectos').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        order: [[1, 'asc']],

        ajax: {
            url: '<?= base_url('admin/proyectos') ?>',
            method: 'GET',
            dataType: "json"
        },
        columnDefs: [{
                orderable: false,
                targets: [12,8,7],
                searchable: false,
                targets: [12,8,7]

            }],
        columns: [{
                'data': 'id'
            },

            {
                'data': 'nombreEmpresa'
            },

            {
                'data': 'nombreSucursal'
            },

            {
                'data': 'nombreTipoDescripcion'
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
                'data': 'nombreCliente'
            },

            {
                'data': 'responsable'
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
                    return `<td class="text-right py-0 align-middle">
                         <div class="btn-group btn-group-sm">
                             <button class="btn btn-warning btnEditProyectos" data-toggle="modal" idProyectos="${data.id}" data-target="#modalAddProyectos">  <i class=" fa fa-edit"></i></button>
                             <button class="btn bg-warning btnImprimirProyecto" idProyecto="${data.id}" ><i class="far fa-file-pdf"></i></button>
                             <button class="btn btn-danger btn-delete" data-id="${data.id}"><i class="fas fa-trash"></i></button>
                         </div>
                         </td>`
                }
            }
        ]
    });



    $(document).on('click', '#btnSaveProyectos', function (e) {


        var idProyectos = $("#idProyectos").val();
        var idEmpresa = $("#idEmpresa").val();
        var idSucursal = $("#idSucursal").val();
        var tipoProyecto = $("#tipoProyecto").val();
        var descripcion = $("#descripcion").val();
        var fechaInicio = $("#fechaInicio").val();
        var fechaFinal = $("#fechaFinal").val();
        var idCliente = $("#idCliente").val();
        var responsable = $("#responsable").val();

        $("#btnSaveProyectos").attr("disabled", true);

        var datos = new FormData();
        datos.append("idProyectos", idProyectos);
        datos.append("idEmpresa", idEmpresa);
        datos.append("idSucursal", idSucursal);
        datos.append("tipoProyecto", tipoProyecto);
        datos.append("descripcion", descripcion);
        datos.append("fechaInicio", fechaInicio);
        datos.append("fechaFinal", fechaFinal);
        datos.append("idCliente", idCliente);
        datos.append("responsable", responsable);


        $.ajax({

            url: "<?= base_url('admin/proyectos/save') ?>",
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

                    tableProyectos.ajax.reload();
                    $("#btnSaveProyectos").removeAttr("disabled");


                    $('#modalAddProyectos').modal('hide');
                } else {

                    Toast.fire({
                        icon: 'error',
                        title: respuesta
                    });

                    $("#btnSaveProyectos").removeAttr("disabled");


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

                $("#btnSaveProyectos").removeAttr("disabled");


            } else if (jqXHR.status == 404) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested page not found [404]" + jqXHR.responseText
                });

                $("#btnSaveProyectos").removeAttr("disabled");

            } else if (jqXHR.status == 500) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Internal Server Error [500]." + jqXHR.responseText
                });


                $("#btnSaveProyectos").removeAttr("disabled");

            } else if (textStatus === 'parsererror') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested JSON parse failed." + jqXHR.responseText
                });

                $("#btnSaveProyectos").removeAttr("disabled");

            } else if (textStatus === 'timeout') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Time out error." + jqXHR.responseText
                });

                $("#btnSaveProyectos").removeAttr("disabled");

            } else if (textStatus === 'abort') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ajax request aborted." + jqXHR.responseText
                });

                $("#btnSaveProyectos").removeAttr("disabled");

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: 'Uncaught Error: ' + jqXHR.responseText
                });


                $("#btnSaveProyectos").removeAttr("disabled");

            }
        })

    });



    /**
     * Carga datos actualizar
     */


    /*=============================================
     EDITAR Proyectos
     =============================================*/
    $(".tableProyectos").on("click", ".btnEditProyectos", function () {

        var idProyectos = $(this).attr("idProyectos");

        var datos = new FormData();
        datos.append("idProyectos", idProyectos);

        $.ajax({

            url: "<?= base_url('admin/proyectos/getProyectos') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
                $("#idProyectos").val(respuesta["id"]);

                $("#idEmpresa").val(respuesta["idEmpresa"]).trigger("change");

                $("#idSucursal").empty();

                var newOptionSucursal = new Option(respuesta["nombreSucursal"], respuesta["idSucursal"], true, true);
                $('#idSucursal').append(newOptionSucursal).trigger('change');
                $("#idSucursal").val(respuesta["idSucursal"]).trigger('change');


                var newOptionTipoProyecto = new Option(respuesta["descripcionTiposProyecto"], respuesta["tipoProyecto"], true, true);
                $('#tipoProyecto').append(newOptionTipoProyecto).trigger('change');
                $("#tipoProyecto").val(respuesta["tipoProyecto"]).trigger('change');

                var newOptionCliente = new Option(respuesta["nombreCliente"], respuesta["idCliente"], true, true);
                $('#idCliente').append(newOptionCliente).trigger('change');
                $("#idCliente").val(respuesta["idCliente"]).trigger('change');

                var newOptionResponsable = new Option(respuesta["nombreUsuario"], respuesta["responsable"], true, true);
                $('#responsable').append(newOptionResponsable).trigger('change');
                $("#responsable").val(respuesta["responsable"]).trigger('change');

                $("#tipoProyecto").val(respuesta["tipoProyecto"]);
                $("#descripcion").val(respuesta["descripcion"]);
                $("#fechaInicio").val(respuesta["fechaInicio"]);
                $("#fechaFinal").val(respuesta["fechaFinal"]);
                $("#idCliente").val(respuesta["idCliente"]);

            }

        })

    })


    /*=============================================
     ELIMINAR proyectos
     =============================================*/
    $(".tableProyectos").on("click", ".btn-delete", function () {

        var idProyectos = $(this).attr("data-id");

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
                            url: `<?= base_url('admin/proyectos') ?>/` + idProyectos,
                            method: 'DELETE',
                        }).done((data, textStatus, jqXHR) => {
                            Toast.fire({
                                icon: 'success',
                                title: jqXHR.statusText,
                            });


                            tableProyectos.ajax.reload();
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
        $("#modalAddProyectos").draggable();

    });


    /**
     * Imprimir factura desde la lista de facturas
     */

    $(".tableProyectos").on("click", '.btnImprimirProyecto', function () {

        var idProyecto = $(this).attr("idProyecto");
        window.open("<?= base_url('admin/proyectos/report') ?>" + "/" + idProyecto, "_blank");

    });

</script>
<?= $this->endSection() ?>
        