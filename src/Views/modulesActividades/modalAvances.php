<!-- Modal Avances -->
<div class="modal fade" id="modalAvance" tabindex="-1" role="dialog" aria-labelledby="modalPayment" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Avance</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-paciente" class="form-horizontal">

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Fecha Avance</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="far fa-calendar-alt"></i></span>
                                </div>
                                <input type="hidden"  name="idActividad" id="idActividad" class="form-control <?= session('error.idActividad') ? 'is-invalid' : '' ?>" value="" placeholder="idActividad" autocomplete="off">
                                <input type="date"  name="fecha" id="fecha" class="form-control <?= session('error.fecha') ? 'is-invalid' : '' ?>" value="<?php echo date('Y-m-d'); ?>" placeholder="fecha" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Descripcion</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-comment"></i></span>
                                </div>
                                <input type="text"  name="descripcionAvance" id="descripcionAvance" class="form-control <?= session('error.descripcionAvance') ? 'is-invalid' : '' ?>" value="" placeholder="Descripcion avance" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Horas</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-clock"></i></span>
                                </div>
                                <input type="text"  name="horas" id="horas" class="form-control <?= session('error.horas') ? 'is-invalid' : '' ?>" value="" placeholder="horas" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="inputName" class="col-sm-2 col-form-label">Porcentaje de Avance</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fa fa-percent"></i></span>
                                </div>
                                <input type="text"  name="porcentaje" id="porcentaje" class="form-control <?= session('error.porcentaje') ? 'is-invalid' : '' ?>" value="" placeholder="porcentaje" autocomplete="off">
                            </div>
                        </div>
                    </div>



                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm btnCerrar" data-dismiss="modal"><?= lang('boilerplate.global.close') ?></button>
                <button type="button" class="btn btn-primary btn-sm btnGuardarAvance" id="btnGuardarAvance">Guardar</button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>


<script>



    $(".tableActividades").on("click", '.btnSetAvance', function () {

        var idActividad = $(this).attr("idActividad");

        console.log("asd");
        
        $("#idActividad").val(idActividad);
        $("#descripcionAvance").val("");
        $("#horas").val("");
        $("#porcentaje").val("");


    });



    /**
     * Save Payment
     */


    $(".btnGuardarAvance").on("click", function () {


        guardarAvance();


    });




    function guardarAvance() {

        var idActividad = $("#idActividad").val();
        var fecha = $("#fecha").val();
        var descripcion = $("#descripcionAvance").val();
        var horas = $("#horas").val();
        var porcentaje = $("#porcentaje").val();




        $(".btnGuardarAvance").attr("disabled", true);


        var datos = new FormData();
        datos.append("idActividad", idActividad);
        datos.append("fecha", fecha);
        datos.append("descripcion", descripcion);
        datos.append("horas", horas);
        datos.append("porcentaje", porcentaje);

        $.ajax({

            url: "<?= base_url('admin/avanceActividad/save') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            //dataType:"json",
            success: function (respuesta) {

                if (respuesta.match(/Correctamente.*/)) {


                    Toast.fire({
                        icon: 'success',
                        title: "Guardado Correctamente"
                    });

                    $(".btnGuardarAvance").removeAttr("disabled");

                    tableActividades.ajax.reload();
                    $(".btnCerrar").trigger("click");

                } else {

                    Toast.fire({
                        icon: 'error',
                        title: respuesta
                    });

                    $(".btnGuardarAvance").removeAttr("disabled");


                }

            }

        })


    }

</script>


<?= $this->endSection() ?>