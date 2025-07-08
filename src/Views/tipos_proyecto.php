<?= $this->include('julio101290\boilerplate\Views\load\select2') ?>
<?= $this->include('julio101290\boilerplate\Views\load\datatables') ?>
<?= $this->include('julio101290\boilerplate\Views\load\nestable') ?>
<!-- Extend from layout index -->
<?= $this->extend('julio101290\boilerplate\Views\layout\index') ?>

<!-- Section content -->
<?= $this->section('content') ?>

<?= $this->include('modulesTipos_proyecto/modalCaptureTipos_proyecto') ?>

<!-- SELECT2 EXAMPLE -->
<div class="card card-default">
    <div class="card-header">
        <div class="float-right">
            <div class="btn-group">

                <button class="btn btn-primary btnAddTipos_proyecto" data-toggle="modal" data-target="#modalAddTipos_proyecto"><i class="fa fa-plus"></i>

                    <?= lang('tipos_proyecto.add') ?>

                </button>

            </div>
        </div>
    </div>
    <div class="card-body">
        <div class="row">
            <div class="col-md-12">
                <div class="table-responsive">
                    <table id="tableTipos_proyecto" class="table table-striped table-hover va-middle tableTipos_proyecto">
                        <thead>
                            <tr>

                                <th>#</th>
                                <th><?= lang('tipos_proyecto.fields.idEmpresa') ?></th>
                                <th><?= lang('tipos_proyecto.fields.descripcion') ?></th>
                                <th><?= lang('tipos_proyecto.fields.created_at') ?></th>
                                <th><?= lang('tipos_proyecto.fields.deleted_at') ?></th>
                                <th><?= lang('tipos_proyecto.fields.updated_at') ?></th>

                                <th><?= lang('tipos_proyecto.fields.actions') ?> </th>

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

    var tableTipos_proyecto = $('#tableTipos_proyecto').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        autoWidth: false,
        order: [[1, 'asc']],

        ajax: {
            url: '<?= base_url('admin/tipos_proyecto') ?>',
            method: 'GET',
            dataType: "json"
        },
        columnDefs: [{
                orderable: false,
                targets: [6],
                searchable: false,
                targets: [6]

            }],
        columns: [{
                'data': 'id'
            },

            {
                'data': 'idEmpresa'
            },

            {
                'data': 'descripcion'
            },

            {
                'data': 'created_at'
            },

            {
                'data': 'deleted_at'
            },

            {
                'data': 'updated_at'
            },

            {
                "data": function (data) {
                    return `<td class="text-right py-0 align-middle">
                         <div class="btn-group btn-group-sm">
                             <button class="btn btn-warning btnEditTipos_proyecto" data-toggle="modal" idTipos_proyecto="${data.id}" data-target="#modalAddTipos_proyecto">  <i class=" fa fa-edit"></i></button>
                             <button class="btn btn-danger btn-delete" data-id="${data.id}"><i class="fas fa-trash"></i></button>
                         </div>
                         </td>`
                }
            }
        ]
    });



    $(document).on('click', '#btnSaveTipos_proyecto', function (e) {


        var idTipos_proyecto = $("#idTipos_proyecto").val();
        var idEmpresa = $("#idEmpresa").val();
        var descripcion = $("#descripcion").val();

        $("#btnSaveTipos_proyecto").attr("disabled", true);

        var datos = new FormData();
        datos.append("idTipos_proyecto", idTipos_proyecto);
        datos.append("idEmpresa", idEmpresa);
        datos.append("descripcion", descripcion);


        $.ajax({

            url: "<?= base_url('admin/tipos_proyecto/save') ?>",
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

                    tableTipos_proyecto.ajax.reload();
                    $("#btnSaveTipos_proyecto").removeAttr("disabled");


                    $('#modalAddTipos_proyecto').modal('hide');
                } else {

                    Toast.fire({
                        icon: 'error',
                        title: respuesta
                    });

                    $("#btnSaveTipos_proyecto").removeAttr("disabled");


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

                $("#btnSaveTipos_proyecto").removeAttr("disabled");


            } else if (jqXHR.status == 404) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested page not found [404]" + jqXHR.responseText
                });

                $("#btnSaveTipos_proyecto").removeAttr("disabled");

            } else if (jqXHR.status == 500) {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Internal Server Error [500]." + jqXHR.responseText
                });


                $("#btnSaveTipos_proyecto").removeAttr("disabled");

            } else if (textStatus === 'parsererror') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Requested JSON parse failed." + jqXHR.responseText
                });

                $("#btnSaveTipos_proyecto").removeAttr("disabled");

            } else if (textStatus === 'timeout') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Time out error." + jqXHR.responseText
                });

                $("#btnSaveTipos_proyecto").removeAttr("disabled");

            } else if (textStatus === 'abort') {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: "Ajax request aborted." + jqXHR.responseText
                });

                $("#btnSaveTipos_proyecto").removeAttr("disabled");

            } else {

                Swal.fire({
                    icon: "error",
                    title: "Oops...",
                    text: 'Uncaught Error: ' + jqXHR.responseText
                });


                $("#btnSaveTipos_proyecto").removeAttr("disabled");

            }
        })

    });



    /**
     * Carga datos actualizar
     */


    /*=============================================
     EDITAR Tipos_proyecto
     =============================================*/
    $(".tableTipos_proyecto").on("click", ".btnEditTipos_proyecto", function () {

        var idTipos_proyecto = $(this).attr("idTipos_proyecto");

        var datos = new FormData();
        datos.append("idTipos_proyecto", idTipos_proyecto);

        $.ajax({

            url: "<?= base_url('admin/tipos_proyecto/getTipos_proyecto') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {
                $("#idTipos_proyecto").val(respuesta["id"]);

                $("#idEmpresa").val(respuesta["idEmpresa"]).trigger("change");
                $("#descripcion").val(respuesta["descripcion"]);


            }

        })

    })


    /*=============================================
     ELIMINAR tipos_proyecto
     =============================================*/
    $(".tableTipos_proyecto").on("click", ".btn-delete", function () {

        var idTipos_proyecto = $(this).attr("data-id");

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
                            url: `<?= base_url('admin/tipos_proyecto') ?>/` + idTipos_proyecto,
                            method: 'DELETE',
                        }).done((data, textStatus, jqXHR) => {
                            Toast.fire({
                                icon: 'success',
                                title: jqXHR.statusText,
                            });


                            tableTipos_proyecto.ajax.reload();
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
        $("#modalAddTipos_proyecto").draggable();

    });


</script>
<?= $this->endSection() ?>
        