<!-- Modal Tipos_proyecto -->
<div class="modal fade" id="modalAddTipos_proyecto" tabindex="-1" role="dialog" aria-labelledby="modalAddTipos_proyecto" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('tipos_proyecto.createEdit') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-tipos_proyecto" class="form-horizontal">
                    <input type="hidden" id="idTipos_proyecto" name="idTipos_proyecto" value="0">

                    <div class="form-group row">
                        <label for="emitidoRecibido" class="col-sm-2 col-form-label">Empresa</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control" name="idEmpresa" id="idEmpresa" style = "width:80%;">
                                    <option value="0">Seleccione empresa</option>
                                    <?php
                                    foreach ($empresas as $key => $value) {

                                        echo "<option value='$value[id]' selected>$value[id] - $value[nombre] </option>  ";
                                    }
                                    ?>

                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-2 col-form-label"><?= lang('tipos_proyecto.fields.descripcion') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="descripcion" id="descripcion" class="form-control <?= session('error.descripcion') ? 'is-invalid' : '' ?>" value="<?= old('descripcion') ?>" placeholder="<?= lang('tipos_proyecto.fields.descripcion') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?= lang('boilerplate.global.close') ?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveTipos_proyecto"><?= lang('boilerplate.global.save') ?></button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>


<script>

    $(document).on('click', '.btnAddTipos_proyecto', function (e) {


        $(".form-control").val("");

        $("#idTipos_proyecto").val("0");

        $("#idEmpresa").val("0").trigger("change");

        $("#btnSaveTipos_proyecto").removeAttr("disabled");

    });

    /* 
     * AL hacer click al editar
     */



    $(document).on('click', '.btnEditTipos_proyecto', function (e) {


        var idTipos_proyecto = $(this).attr("idTipos_proyecto");

        //LIMPIAMOS CONTROLES
        $(".form-control").val("");

        $("#idEmpresa").val("0").trigger("change");

        $("#idTipos_proyecto").val(idTipos_proyecto);
        $("#btnGuardarTipos_proyecto").removeAttr("disabled");

    });


    $("#idEmpresa").select2();

</script>


<?= $this->endSection() ?>
        