<!-- Modal Unidades_medida -->
<div class="modal fade" id="modalAddUnidades_medida" tabindex="-1" role="dialog" aria-labelledby="modalAddUnidades_medida" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('unidades_medida.createEdit') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-unidades_medida" class="form-horizontal">
                    <input type="hidden" id="idUnidades_medida" name="idUnidades_medida" value="0">

                    <div class="form-group row">
                        <label for="emitidoRecibido" class="col-sm-2 col-form-label">Empresa</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control idEmpresa" name="idEmpresa" id="idEmpresa" style = "width:80%;">
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
                        <label for="descripcion" class="col-sm-2 col-form-label"><?= lang('unidades_medida.fields.descripcion') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="descripcion" id="descripcion" class="form-control <?= session('error.descripcion') ? 'is-invalid' : '' ?>" value="<?= old('descripcion') ?>" placeholder="<?= lang('unidades_medida.fields.descripcion') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?= lang('boilerplate.global.close') ?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveUnidades_medida"><?= lang('boilerplate.global.save') ?></button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>


<script>

    $(document).on('click', '.btnAddUnidades_medida', function (e) {


        $(".form-control").val("");

        $("#idUnidades_medida").val("0");

        $("#btnSaveUnidades_medida").removeAttr("disabled");

        $("#idEmpresa").val("0").trigger("change");

    });

    /* 
     * AL hacer click al editar
     */



    $(document).on('click', '.btnEditUnidades_medida', function (e) {


        var idUnidades_medida = $(this).attr("idUnidades_medida");

        //LIMPIAMOS CONTROLES
        $(".form-control").val("");

        $("#idUnidades_medida").val(idUnidades_medida);
        $("#btnGuardarUnidades_medida").removeAttr("disabled");

        $("#idEmpresa").val("0").trigger("change");

    });


    $("#idEmpresa").select2();

</script>


<?= $this->endSection() ?>
        