<?= $this->include('julio101290\boilerplate\Views\load\select2') ?>
<?= $this->include('julio101290\boilerplate\Views\load\datatables') ?>
<?= $this->include('julio101290\boilerplate\Views\load\nestable') ?>
<!-- Extend from layout index -->
<?= $this->extend('julio101290\boilerplate\Views\layout\index') ?>

<!-- Section content -->
<?= $this->section('content') ?>

<?= $this->include('julio101290\boilerplateprojects\Views\modulesConceptos/modalCaptureConceptos') ?>

<!-- SELECT2 EXAMPLE -->
<div class="card card-default">
    <div class="card-header">
        <div class="float-right">
            <div class="btn-group">

                <button class="btn btn-primary btnAddConceptos" data-toggle="modal" data-target="#modalAddConceptos"><i class="fa fa-plus"></i>

                    <?= lang('conceptos.add') ?>

                </button>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="tableConceptos" class="table table-striped table-hover va-middle tableConceptos">
                        <thead>
                            <tr>

                                <th>#</th>
                                <th><?= lang('conceptos.fields.idEmpresa') ?></th>
                                <th><?= lang('conceptos.fields.descripcion') ?></th>
                                <th><?= lang('conceptos.fields.tipoProyecto') ?></th>
                                <th><?= lang('conceptos.fields.orden') ?></th>
                                <th><?= lang('conceptos.fields.created_at') ?></th>
                                <th><?= lang('conceptos.fields.updated_at') ?></th>
                                <th><?= lang('conceptos.fields.deleted_at') ?></th>

                                <th><?= lang('conceptos.fields.actions') ?> </th>

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

    var tableConceptos = $('#tableConceptos').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        order: [[1, 'asc']],

        ajax: {
            url: '<?= base_url('admin/conceptos') ?>',
            method: 'GET',
            dataType: "json"
        },
        columnDefs: [{
                orderable: false,
                targets: [8],
                searchable: false,
                targets: [8]

            }],
        columns: [{
                'data': 'id'
            },

            {
                'data': 'nombreEmpresa'
            },

            {
                'data': 'descripcion'
            },

            {
                'data': 'tipoProyectoNombre'
            },

            {
                'data': 'orden'
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
                             <button class="btn btn-warning btnEditConceptos" data-toggle="modal" idConceptos="${data.id}" data-target="#modalAddConceptos">  <i class=" fa fa-edit"></i></button>
                             <button class="btn btn-danger btn-delete" data-id="${data.id}"><i class="fas fa-trash"></i></button>
                         </div>
                         </td>`
                }
            }
        ]
    });



    $(document).on('click', '#btnSaveConceptos', function (e) {


        var idConceptos = $("#idConceptos").val();
        var idEmpresa = $("#idEmpresa").val();
        var descripcion = $("#descripcion").val();
        var orden = $("#orden").val();
        var tipoProyecto = $("#tipoProyecto").val();

        $("#btnSaveConceptos").attr("disabled", true);

        var datos = new FormData();
        datos.append("idConceptos", idConceptos);
        datos.append("idEmpresa", idEmpresa);
        datos.append("descripcion", descripcion);
        datos.append("orden", orden);
        datos.append("tipoProyecto", tipoProyecto);


        $.ajax({

            url: "<?= base_url('admin/conceptos/save') ?>",
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

                    tableConceptos.ajax.reload();
                    $("#btnSaveConceptos").removeAttr("disabled");


                    $('#modalAddConceptos').modal('hide');
                } else {

                    Toast.fire({
                        icon: 'error',
                        title: respuesta
                    });

                    $("#btnSaveConceptos").removeAttr("disabled");


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

                $("#btnSaveConceptos").removeAttr("disabled");


            } else if (jqXHR.status == 404) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested page not found [404]" + jqXHR.responseText
                });

                $("#btnSaveConceptos").removeAttr("disabled");

            } else if (jqXHR.status == 500) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Internal Server Error [500]." + jqXHR.responseText
                });


                $("#btnSaveConceptos").removeAttr("disabled");

            } else if (textStatus === 'parsererror') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested JSON parse failed." + jqXHR.responseText
                });

                $("#btnSaveConceptos").removeAttr("disabled");

            } else if (textStatus === 'timeout') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Time out error." + jqXHR.responseText
                });

                $("#btnSaveConceptos").removeAttr("disabled");

            } else if (textStatus === 'abort') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ajax request aborted." + jqXHR.responseText
                });

                $("#btnSaveConceptos").removeAttr("disabled");

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: 'Uncaught Error: ' + jqXHR.responseText
                });


                $("#btnSaveConceptos").removeAttr("disabled");

            }
        })

    });



    /**
     * Carga datos actualizar
     */


    /*=============================================
     EDITAR Conceptos
     =============================================*/
    $(".tableConceptos").on("click", ".btnEditConceptos", function () {

        var idConceptos = $(this).attr("idConceptos");

        var datos = new FormData();
        datos.append("idConceptos", idConceptos);

        $.ajax({

            url: "<?= base_url('admin/conceptos/getConceptos') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
                $("#idConceptos").val(respuesta["id"]);

                $("#idEmpresa").val(respuesta["idEmpresa"]).trigger("change");
                $("#descripcion").val(respuesta["descripcion"]);

                $("#orden").val(respuesta["orden"]);

                $("#tipoProyecto").empty();
                var newOptionTipoProyecto = new Option(respuesta["descripcionTiposProyecto"], respuesta["tipoProyecto"], true, true);
                $('#tipoProyecto').append(newOptionTipoProyecto).trigger('change');
                $("#tipoProyecto").val(respuesta["tipoProyecto"]).trigger('change');


            }

        })

    })


    /*=============================================
     ELIMINAR conceptos
     =============================================*/
    $(".tableConceptos").on("click", ".btn-delete", function () {

        var idConceptos = $(this).attr("data-id");

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
                            url: `<?= base_url('admin/conceptos') ?>/` + idConceptos,
                            method: 'DELETE',
                        }).done((data, textStatus, jqXHR) => {
                            Toast.fire({
                                icon: 'success',
                                title: jqXHR.statusText,
                            });


                            tableConceptos.ajax.reload();
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
        $("#modalAddConceptos").draggable();

    });


</script>
<?= $this->endSection() ?>
        