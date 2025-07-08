<!-- Modal Actividades -->
<div class="modal fade" id="modalAddActividades" tabindex="-1" role="dialog" aria-labelledby="modalAddActividades" aria-hidden="true" lang="es-MX">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><?= lang('actividades.createEdit') ?></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="form-actividades" class="form-horizontal">
                    <input type="hidden" id="idActividades" name="idActividades" value="0">

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
                        <label for="idProyecto" class="col-sm-2 col-form-label"><?= lang('actividades.fields.idProyecto') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control idProyecto" name="idProyecto" id="idProyecto" style = "width:80%;">
                                    <option value="0">Seleccione Proyecto</option>

                                </select>

                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="etapa" class="col-sm-2 col-form-label"><?= lang('actividades.fields.etapa') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control etapa" name="etapa" id="etapa" style = "width:80%;">
                                    <option value="0">Seleccione Etapa</option>

                                </select>

                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="concepto" class="col-sm-2 col-form-label"><?= lang('actividades.fields.concepto') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control concepto" name="concepto" id="concepto" style = "width:80%;">
                                    <option value="0">Seleccione Concepto</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="descripcion" class="col-sm-2 col-form-label"><?= lang('actividades.fields.descripcion') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="text" name="descripcion" id="descripcion" class="form-control <?= session('error.descripcion') ? 'is-invalid' : '' ?>" value="<?= old('descripcion') ?>" placeholder="<?= lang('actividades.fields.descripcion') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="fechaInicio" class="col-sm-2 col-form-label"><?= lang('actividades.fields.fechaInicio') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="datetime-local" name="fechaInicio" id="fechaInicio" class="form-control <?= session('error.fechaInicio') ? 'is-invalid' : '' ?>" value="<?= old('fechaInicio') ?>" placeholder="<?= lang('actividades.fields.fechaInicio') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="fechaFinal" class="col-sm-2 col-form-label"><?= lang('actividades.fields.fechaFinal') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="datetime-local" name="fechaFinal" id="fechaFinal" class="form-control <?= session('error.fechaFinal') ? 'is-invalid' : '' ?>" value="<?= old('fechaFinal') ?> " placeholder="<?= lang('actividades.fields.fechaFinal') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="producto" class="col-sm-2 col-form-label"><?= lang('actividades.fields.producto') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control producto" name="producto" id="producto" style = "width:80%;">
                                    <option value="0">producto</option>

                                </select>

                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="costoUnitario" class="col-sm-2 col-form-label"><?= lang('actividades.fields.costoUnitario') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="number" step='0.00001' name="costoUnitario" id="costoUnitario" class="form-control <?= session('error.costoUnitario') ? 'is-invalid' : '' ?>" value="<?= old('costoUnitario') ?>" placeholder="<?= lang('actividades.fields.costoUnitario') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="cantEstimada" class="col-sm-2 col-form-label"><?= lang('actividades.fields.cantEstimada') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="number" name="cantEstimada" id="cantEstimada" class="form-control <?= session('error.cantEstimada') ? 'is-invalid' : '' ?>" value="<?= old('cantEstimada') ?>" placeholder="<?= lang('actividades.fields.cantEstimada') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" >
                        <label for="cantReal" class="col-sm-2 col-form-label"><?= lang('actividades.fields.cantReal') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="number" step="0.000001" disabled name="cantReal" id="cantReal" class="form-control <?= session('error.cantReal') ? 'is-invalid' : '' ?>" value="<?= old('cantReal') ?>" placeholder="<?= lang('actividades.fields.cantReal') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" >
                        <label for="costoTotalEstimado" class="col-sm-2 col-form-label"><?= lang('actividades.fields.costoTotalEstimado') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="number" disabled step="0.000001" disabled name="costoTotalEstimado" id="costoTotalEstimado" class="form-control <?= session('error.costoTotalEstimado') ? 'is-invalid' : '' ?>" value="<?= old('costoTotalEstimado') ?>" placeholder="<?= lang('actividades.fields.costoTotalEstimado') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class="form-group row" >
                        <label for="costoTotalReal" class="col-sm-2 col-form-label"><?= lang('actividades.fields.costoTotalReal') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>
                                <input type="number" disabled step="0.000001" disabled name="costoTotalReal" id="costoTotalReal" class="form-control <?= session('error.costoTotalReal') ? 'is-invalid' : '' ?>" value="<?= old('costoTotalReal') ?>" placeholder="<?= lang('actividades.fields.costoTotalReal') ?>" autocomplete="off">
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="unidadMedida" class="col-sm-2 col-form-label"><?= lang('actividades.fields.unidadMedida') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>

                                <select class="form-control unidadMedida" name="unidadMedida" id="unidadMedida" style = "width:80%;">
                                    <option value="0">Seleccione Unidad de Medida</option>

                                </select>

                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label"><?= lang('actividades.fields.status') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>


                                <select class="form-control status" name="status" id="status" style = "width:80%;">

                                    <option value="01" selected>Pendiente</option>
                                    <option value="02">Terminado</option>
                                    <option value="03">Cancelado</option>

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label"><?= lang('actividades.fields.modalidadActividad') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>


                                <select class="form-control modalidadActividad" name="modalidadActividad" id="modalidadActividad" style = "width:80%;">

                                    <option value="01" selected>Interna</option>
                                    <option value="02">Subcontratada</option>
                                    <option value="03">Insumo</option>

                                </select>
                            </div>
                        </div>
                    </div>


                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label"><?= lang('actividades.fields.usuario') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>


                                <select class="form-control idUsuario" name="idUsuario" id="idUsuario" style = "width:80%;">

                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="form-group row">
                        <label for="status" class="col-sm-2 col-form-label"><?= lang('actividades.fields.proveedor') ?></label>
                        <div class="col-sm-10">
                            <div class="input-group">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-pencil-alt"></i></span>
                                </div>


                                <select class="form-control idProveedor" name="idProveedor" id="idProveedor" style = "width:80%;">

                                </select>
                            </div>
                        </div>
                    </div>




                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal"><?= lang('boilerplate.global.close') ?></button>
                <button type="button" class="btn btn-primary btn-sm" id="btnSaveActividades"><?= lang('boilerplate.global.save') ?></button>
            </div>
        </div>
    </div>
</div>

<?= $this->section('js') ?>


<script>

    $(document).on('click', '.btnAddActividades', function (e) {


        $(".form-control").val("");

        $("#idActividades").val("0");

        $("#idEmpresa").val("0").trigger("change");

        $("#btnSaveActividades").removeAttr("disabled");

        $("#status").val("01");

        $("#idProyecto").empty();

        $("#etapa").empty();

        $("#concepto").empty();

        $("#unidadMedida").empty();

        $("#modalidadActividad").val("01");

        $("#costoUnitario").val("0.00");

        $("#costoTotalEstimado").val("0.00");

        $("#costoTotalReal").val("0.00");

    });

    /* 
     * AL hacer click al editar
     */



    $(document).on('click', '.btnEditActividades', function (e) {


        var idActividades = $(this).attr("idActividades");

        //LIMPIAMOS CONTROLES
        $(".form-control").val("");

        $("#idActividades").val(idActividades);
        $("#btnGuardarActividades").removeAttr("disabled");

    });


    $("#idEmpresa").select2();


    $("#idProyecto").select2({
        ajax: {
            url: "<?= site_url('admin/proyecto/getProyectosAjax') ?>",
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


    $("#etapa").select2({
        ajax: {
            url: "<?= site_url('admin/etapas/getEtapasActividadesAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresa').val(); // CSRF hash
                var idProyecto = $('.idProyecto').val(); // CSRF hash

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idProyecto: idProyecto, // CSRF Token
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


    $("#concepto").select2({
        ajax: {
            url: "<?= site_url('admin/conceptos/getConceptosActividadesAjax') ?>",
            type: "post",
            dataType: 'json',
            delay: 250,
            data: function (params) {
                // CSRF Hash
                var csrfName = $('.txt_csrfname').attr('name'); // CSRF Token name
                var csrfHash = $('.txt_csrfname').val(); // CSRF hash
                var idEmpresa = $('.idEmpresa').val();
                var idProyecto = $('.idProyecto').val();

                return {
                    searchTerm: params.term, // search term
                    [csrfName]: csrfHash, // CSRF Token
                    idProyecto: idProyecto,
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


    $("#unidadMedida").select2({
        ajax: {
            url: "<?= site_url('admin/unidades_medida/getUnidadesAjax') ?>",
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
    $("#idUsuario").select2({
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


    // Obtenemos los Proveedores
    $("#idProveedor").select2({
        ajax: {
            url: "<?= site_url('admin/proveedores/getProveedoresAjax') ?>",
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
     * AJAX Para productos
     */

    $("#producto").select2({
        ajax: {
            url: "<?= site_url('admin/products/getProductsAjax') ?>",
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
     * Obtenemos el dato del producto para calcular costos
     */

    $("#producto").on("change", function () {

        console.log("Valor Producto", $(this).val());
        var idEmpresa = $("#idEmpresa").val();
        var idProducts = $(this).val();

        var datos = new FormData();
        datos.append("idProducts", idProducts);



        if (idEmpresa == 0) {

            Toast.fire({
                icon: 'error',
                title: "Tiene que seleccionar la empresa"
            });

        }

        $.ajax({

            url: "<?= base_url('admin/products/getProducts') ?>",
            method: "POST",
            data: datos,
            cache: false,
            contentType: false,
            processData: false,
            dataType: "json",
            success: function (respuesta) {

                var salePrice = respuesta["salePrice"];
                var porcentTax = respuesta["porcentTax"];
                var porcentIVARetenido = respuesta["porcentIVARetenido"];
                var porcentISRRetenido = respuesta["porcentISRRetenido"];

                var cantEstimada = $("#cantEstimada").val();
                var cantReal = $("#cantReal").val();
                var costoEstimadoTotal = 0;
                var costoRealTotal = 0;

                var cantEstimada = $("#cantEstimada").val();
                var cantReal = $("#cantReal").val();

                if (porcentTax > 0) {

                    var tax = ((porcentTax * 0.01)) * salePrice;



                } else {

                    var tax = 0;
                }

                if (porcentIVARetenido > 0) {

                    var IVARetenido = ((porcentIVARetenido * 0.01)) * salePrice;


                } else {

                    var IVARetenido = 0;

                }

                if (porcentISRRetenido > 0) {

                    var ISRRetenido = ((porcentISRRetenido * 0.01)) * salePrice;


                } else {

                    var ISRRetenido = 0;

                }


                var neto = (((porcentTax * 0.01) + 1) * salePrice) - (IVARetenido + ISRRetenido);

                //Asignamos el costo
                $("#costoUnitario").val(neto).trigger("change");

                if (cantEstimada == "") {

                    cantEstimada = 0;

                }

                if (cantReal == "") {

                    cantReal = 0;

                }

                costoEstimadoTotal = cantEstimada * neto;

                costoRealTotal = cantReal * neto;

                $("#costoTotalEstimado").val(costoEstimadoTotal);

                $("#costoTotalReal").val(costoRealTotal);


            }

        })

    });

    /**
     * Cuando el costo unitario cambie
     */

    $("#costoUnitario").on("change", function () {

        var costo = $(this).val();
        var cantEstimada = $("#cantEstimada").val();
        var cantReal = $("#cantReal").val();
        var costoEstimadoTotal = 0;
        var costoRealTotal = 0;

        if (costo == "") {

            costo = 0;
        }

        if (cantEstimada == "") {

            cantEstimada = 0;

        }

        if (cantReal == "") {

            cantReal = 0;

        }

        costoEstimadoTotal = cantEstimada * costo;

        costoRealTotal = cantReal * costo;

        $("#costoTotalEstimado").val(costoRealTotal);

        $("#costoTotalReal").val(costoRealTotal);


    });


    /**
     * Cuando la cantidad estimada cambie
     */

    $("#cantEstimada").on("change", function () {

        var costo = $("#costoUnitario").val();
        var cantEstimada = $(this).val();
        var cantReal = $("#cantReal").val();
        var costoEstimadoTotal = 0;
        var costoRealTotal = 0;

        if (costo == "") {

            costo = 0;
        }

        if (cantEstimada == "") {

            cantEstimada = 0;

        }

        costoEstimadoTotal = cantEstimada * costo;

        $("#costoTotalEstimado").val(costoEstimadoTotal);

    });

</script>


<?= $this->endSection() ?>
        