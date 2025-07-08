<!-- Modal Etapas -->
<div class="modal fade" id="modalAddEtapas" tabindex="-1" role="dialog" aria-labelledby="modalAddEtapas" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('etapas.createEdit') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-etapas" class="form-horizontal">
                    <input type="hidden" id="idEtapas" name="idEtapas" value="0">

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
                        <label for="descripcion" class="col-sm-2 col-form-label"><?= lang('etapas.fields.descripcion') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="descripcion" id="descripcion" class="form-control <?= session('error.descripcion') ? 'is-invalid' : '' ?>" value="<?= old('descripcion') ?>" placeholder="<?= lang('etapas.fields.descripcion') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-2 col-form-label"><?= lang('etapas.fields.tipoProyecto') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control tipoProyecto" name="tipoProyecto" id="tipoProyecto" style = "width:80%;">
                                    <option value="0">Seleccione el Tipo de Proyecto</option>
                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-2 col-form-label"><?= lang('etapas.fields.orden') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="number" name="orden" id="orden" class="form-control orden <?= session('error.descripcion') ? 'is-invalid' : '' ?>" value="<?= old('orden') ?>" placeholder="<?= lang('etapas.fields.orden') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?= lang('boilerplate.global.close') ?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveEtapas"><?= lang('boilerplate.global.save') ?></button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>


<script>

    $(document).on('click', '.btnAddEtapas', function (e) {


        $(".form-control").val("");

        $("#idEtapas").val("0");

        $("#idEmpresa").val("0").trigger("change");

        $("#btnSaveEtapas").removeAttr("disabled");

    });

    /* 
     * AL hacer click al editar
     */



    $(document).on('click', '.btnEditEtapas', function (e) {


        var idEtapas = $(this).attr("idEtapas");

        //LIMPIAMOS CONTROLES
        $(".form-control").val("");

        $("#idEtapas").val(idEtapas);
        $("#btnGuardarEtapas").removeAttr("disabled");

    });


    $("#idEmpresa").select2();
    
      /**
     * PETICION AJAX PARA TIPOS DE PROYECTOS
     */


    $("#tipoProyecto").select2({
        ajax: {
            url: "<?= site_url('admin/tiposProyectos/getTiposProyectosAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresa').val(); // CSRF hash

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
        