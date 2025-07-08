<!-- Modal Pacientes -->
<div class="modal fade" id="modalListaAvances" tabindex="-1" role="dialog" aria-labelledby="modalListaAvances"
     aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Lista de Avances</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">
                        <div class="table-responsive">
                            <table id="table-avances" class="table table-striped table-hover va-middle tableAvances">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Fecha</th>
                                        <th>Descripcion</th>
                                        <th>Porcentaje Avanzado</th>
                                        <th>Horas</th>
                                        <th>Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">
                    <?= lang('boilerplate.global.close') ?>
                </button>

            </div>
        </div>
    </div>
</div>



<?= $this->section('js') ?>


<script>


    var tablaAvances = $('#table-avances').DataTable({
        processing: true,
        serverSide: true,
        autoWidth: false,
        order: [[1, 'asc']],
        ajax: {
            url: '<?= base_url('admin/avanceActividad/getAvances') ?>/0',
            method: 'GET',
            dataType: "json"
        },
        columnDefs: [{
                orderable: false,
                targets: [5],
                searchable: false,
                targets: [5]

            }],
        columns: [{
                'data': 'id'
            },
            {
                'data': 'fecha'
            },
            {
                'data': 'descripcion'
            },
            {
                'data': 'porcentaje'
            },
            {
                'data': 'horas'
            },


            {
                "data": function (data) {
                    return `<td class="text-right py-0 align-middle">
                            <div class="btn-group btn-group-sm">
                                <button class="btn-danger btn-delete btnDeleteAvance" data-id="${data.id}" ><i class="fas fa-trash"></i></button>
                            </div>
                            </td>`
                }
            }
        ]
    });
    /**
     * Eliminar Renglon Diagnostico
     */

    $("#table-avances").on("click", ".btnDeleteAvance", function () {

        var idAvance = $(this).attr("data-id");
        
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
                            url: `<?= base_url('admin/avanceActividad/delete/') ?>/` + idAvance,
                            method: 'GET',
                        }).done((data, textStatus, jqXHR) => {
                            Toast.fire({
                                icon: 'success',
                                title: jqXHR.statusText,
                            });
                            tablaAvances.ajax.reload();
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










</script>


<?= $this->endSection() ?>