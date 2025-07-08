<!-- Modal Proyectos -->
<div class="modal fade" id="modalAddProyectos" tabindex="-1" role="dialog" aria-labelledby="modalAddProyectos" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('proyectos.createEdit') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-proyectos" class="form-horizontal">
                    <input type="hidden" id="idProyectos" name="idProyectos" value="0">

                    <div class="form-group row">
                        <label for="emitidoRecibido" class="col-sm-2 col-form-label">Empresa</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control idEmpresa controlModalETC" name="idEmpresa" id="idEmpresa" style = "width:80%;">
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
                        <label for="sucursal" class="col-sm-2 col-form-label">Sucursal</label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control idSucursal" name="idSucursal" id="idSucursal" style = "width:80%;">
                                    <option value="0">Seleccione sucursal</option>


                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="tipoProyecto" class="col-sm-2 col-form-label"><?= lang('proyectos.fields.tipoProyecto') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control tipoProyecto" name="tipoProyecto" id="tipoProyecto" style = "width:80%;">
                                    <option value="0">Seleccione tipo de proyecto</option>


                                </select>

                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="responsable" class="col-sm-2 col-form-label"><?= lang('proyectos.fields.descripcion') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="descripcion" id="descripcion" class="form-control <?= session('error.descripcion') ? 'is-invalid' : '' ?>" value="<?= old('descripcion') ?> descripcion" placeholder="<?= lang('proyectos.fields.descripcion') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="fechaInicio" class="col-sm-2 col-form-label"><?= lang('proyectos.fields.fechaInicio') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="datetime-local" name="fechaInicio" id="fechaInicio" class="form-control controlModalETC <?= session('error.fechaInicio') ? 'is-invalid' : '' ?>" value="<?= old('fechaInicio') ?>" placeholder="<?= lang('proyectos.fields.fechaInicio') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fechaFinal" class="col-sm-2 col-form-label"><?= lang('proyectos.fields.fechaFinal') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="datetime-local" name="fechaFinal" id="fechaFinal" class="form-control <?= session('error.fechaFinal') ? 'is-invalid' : '' ?>" value="<?= old('fechaFinal') ?>" placeholder="<?= lang('proyectos.fields.fechaFinal') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="idCliente" class="col-sm-2 col-form-label"><?= lang('proyectos.fields.idCliente') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control idCliente" name="idCliente" id="idCliente" style = "width:80%;">
                                    <option value="0">Seleccione cliente</option>


                                </select>

                            </div>
                        </div>
                    </div>
    
                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label"><?= lang('proyectos.fields.responsable') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>


                                <select class="form-control responsable" name="responsable" id="responsable" style = "width:80%;">

                                </select>
                            </div>
                        </div>
                    </div>


                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?= lang('boilerplate.global.close') ?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveProyectos"><?= lang('boilerplate.global.save') ?></button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>


<script>

    $(document).on('click', '.btnAddProyectos', function (e) {


        $(".form-control").val("");

        $("#idProyectos").val("0");

        $("#idEmpresa").val("0").trigger("change");

        $("#btnSaveProyectos").removeAttr("disabled");

    });

    /* 
     * AL hacer click al editar
     */



    $(document).on('click', '.btnEditProyectos', function (e) {


        var idProyectos = $(this).attr("idProyectos");

        //LIMPIAMOS CONTROLES
        $(".form-control").val("");

        $("#idProyectos").val(idProyectos);
        $("#btnGuardarProyectos").removeAttr("disabled");

    });


    $("#idEmpresa").select2();

    $("#idSucursal").select2({
        ajax: {
            url: "<?= site_url('admin/sucursales/getSucursalesAjax') ?>",
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


    /**
     * Peticion Ajax para obtener los clientes
     */
    $("#idCliente").select2({
        ajax: {
            url: "<?= site_url('admin/custumers/getCustumersAjax') ?>",
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
    
    
        // Obtenemos los usuarios
    $("#responsable").select2({
        ajax: {
            url: "<?= site_url('admin/usuarios/getUsuariosEmpresaAjaxSelect2') ?>",
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
        