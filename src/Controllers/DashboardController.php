<?php

namespace julio101290\boilerplatesells\Controllers;
use App\Controllers\BaseController;

use julio101290\boilerplateCFDI\Models\XmlModel;
use julio101290\boilerplatecompanies\Models\EmpresasModel;
use julio101290\boilerplatesells\Models\SellsModel;
use julio101290\boilerplatebranchoffice\Models\BranchofficesModel;
use CodeIgniter\API\ResponseTrait;
//use App\Models\ProyectosModel;
//use App\Models\ActividadesModel;

/**
 * Class DashboardController.
 */
class DashboardController extends BaseController {

    protected $xml;
    protected $empresa;
    protected $sells;
    protected $branchoffice;
    protected $proyectos;
    protected $actividades;

    use ResponseTrait;

    public function __construct() {

        $this->xml = new XmlModel();
        $this->empresa = new EmpresasModel();
        $this->sells = new SellsModel();
        $this->branchoffice = new BranchofficesModel();
     //   $this->proyectos = new ProyectosModel();
     //   $this->actividades = new ActividadesModel();

        helper('menu');
    }

    public function index() {

        helper('auth');
        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('login');
        }

        helper('auth');

        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);
        $titulos["sucursales"] = $this->branchoffice->mdlSucursalesPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
            $empresasRFC[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
            $empresasRFC = array_column($titulos["empresas"], "rfc");
        }


        if (count($titulos["sucursales"]) == "0") {

            $sucursalessID[0] = "0";
        } else {

            $sucurssalesID = array_column($titulos["sucursales"], "id");
        }


        $xmlTotalEmitido = $this->xml->selectSum("total")
                        ->whereIn("idEmpresa", $empresasID)
                        ->whereIn("rfcEmisor", $empresasRFC)
                        ->whereIn("tipoComprobante", ["i", "n"])->first();

        $xmlTotalNomina = $this->xml->selectSum("total")
                        ->whereIn("idEmpresa", $empresasID)
                        ->whereIn("rfcReceptor", $empresasRFC)
                        ->whereIn("tipoComprobante", ["n"])->first();

        $xmlGastosFacturados = $this->xml->selectSum("total")
                        ->whereIn("idEmpresa", $empresasID)
                        ->whereIn("rfcReceptor", $empresasRFC)
                        ->whereIn("tipoComprobante", ["i"])->first();

        $totalEmitido = $xmlTotalEmitido["total"];
        $TotalNomina = $xmlTotalNomina["total"];

        $TotalGastos = $xmlGastosFacturados["total"];

        $saldo = ($totalEmitido + $TotalNomina) - $TotalGastos;

        $productos = $this->sells->mdlVentasPorProductosAgrupado(0
                , 0
                , 0
                , '1990-01-01'
                , '2048-01-01'
                , $empresasID
                , $sucurssalesID);

        $productosDatos["nombre"] = "";
        $productosDatos["cantidad"] = "";
        $productosDatos["color"] = "";

        $colors[1] = "#f56954";
        $colors[2] = "#00a65a";
        $colors[3] = "#f39c12";
        $colors[4] = "#00c0ef";
        $colors[5] = "#3c8dbc";
        $colors[6] = "#d2d6de";
        $colors[7] = "#009900";
        $colors[8] = "#86134d";
        $colors[9] = "#0033cc";
        $colors[10] = "#cc0000";

        $contador = 1;
        foreach ($productos as $key => $value) {

            $productosDatos["nombre"] .= "'$value[description]',";
            $productosDatos["cantidad"] .= "$value[cant],";
            $productosDatos["color"] .= "'$colors[$contador]',";

            $contador++;
        }






        $data = [
            'title' => 'Tablero',
            'totalEmitido' => number_format($totalEmitido, 2, ".", ","),
            'totalNomina' => number_format($TotalNomina, 2, ".", ","),
            'totalGastos' => number_format($TotalGastos, 2, ".", ","),
            'saldo' => number_format($saldo, 2, ".", ","),
        ];

        $productos["nombreProducto"] = substr($productosDatos["nombre"], 0, -1);
        $productos["cantidadProducto"] = substr($productosDatos["cantidad"], 0, -1);
        $productos["colorProducto"] = substr($productosDatos["color"], 0, -1);

        $data["productos"] = $productos;

        return view('julio101290\boilerplatesells\Views\dashboard', $data);
    }

    public function traerInfo($desdeFecha, $hastaFecha) {

        helper('auth');
        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('login');
        }

        helper('auth');

        $idUser = user()->id;

        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);
        $titulos["sucursales"] = $this->branchoffice->mdlSucursalesPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
            $empresasRFC[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
            $empresasRFC = array_column($titulos["empresas"], "rfc");
        }



        if (count($titulos["sucursales"]) == "0") {

            $sucursalessID[0] = "0";
        } else {

            $sucurssalesID = array_column($titulos["sucursales"], "id");
        }


        $datos = $this->xml->getIngresosXMLGrafica($empresasID, $empresasRFC, $desdeFecha, $hastaFecha)->getResultArray();

        $xmlTotalEmitido = $this->xml->selectSum("total")
                        ->whereIn("idEmpresa", $empresasID)
                        ->whereIn("rfcEmisor", $empresasRFC)
                        ->where('fechaTimbrado >=', $desdeFecha . ' 00:00:00')
                        ->where('fechaTimbrado <=', $hastaFecha . ' 23:59:59')
                        ->whereIn("tipoComprobante", ["I", "N"])->first();

        $xmlTotalNomina = $this->xml->selectSum("total")
                        ->whereIn("idEmpresa", $empresasID)
                        ->whereIn("rfcReceptor", $empresasRFC)
                        ->where('fechaTimbrado >=', $desdeFecha . ' 00:00:00')
                        ->where('fechaTimbrado <=', $hastaFecha . ' 23:59:59')
                        ->whereIn("tipoComprobante", ["N"])->first();

        $xmlGastosFacturados = $this->xml->selectSum("total")
                        ->whereIn("idEmpresa", $empresasID)
                        ->whereIn("rfcReceptor", $empresasRFC)
                        ->where('fechaTimbrado >=', $desdeFecha . ' 00:00:00')
                        ->where('fechaTimbrado <=', $hastaFecha . ' 23:59:59')
                        ->whereIn("tipoComprobante", ["I"])->first();

        $saldo = ($xmlTotalEmitido["total"] + $xmlTotalNomina["total"]) - $xmlGastosFacturados["total"];

        $productos = $this->sells->mdlVentasPorProductosAgrupado(0
                , 0
                , 0
                , $desdeFecha
                , $hastaFecha
                , $empresasID
                , $sucurssalesID);

        $productosDatos["nombre"] = "";
        $productosDatos["cantidad"] = "";
        $productosDatos["color"] = "";

        $colors[0] = "#f56954";
        $colors[1] = "#00a65a";
        $colors[2] = "#f39c12";
        $colors[3] = "#00c0ef";
        $colors[4] = "#3c8dbc";
        $colors[5] = "#d2d6de";
        $colors[6] = "#009900";
        $colors[7] = "#86134d";
        $colors[8] = "#0033cc";
        $colors[9] = "#cc0000";

        $contador = 0;
        foreach ($productos as $key => $value) {


            $productos[$contador]["color"] = $colors[$contador];

            $contador++;
        }

        $datosExportar["nombreProducto"] = array_column($productos, "description");
        $datosExportar["cantidadProducto"] = array_column($productos, "cant");
        $datosExportar["colorProducto"] = array_column($productos, "color");

        $data["productos"] = $productosDatos;

        $datosExportar["totalEmitido"] = number_format($xmlTotalEmitido["total"], "2", ".");
        $datosExportar["xmlTotalNomina"] = number_format($xmlTotalNomina["total"], "2", ".");
        $datosExportar["xmlGastosFacturados"] = number_format($xmlGastosFacturados["total"], "2", ".");
        $datosExportar["saldo"] = number_format($saldo, "2", ".");

        $datosExportar["periodo"] = array_column($datos, "periodo");
        $datosExportar["ingresos"] = array_column($datos, "ingreso");
        $datosExportar["egresos"] = array_column($datos, "egreso");

        /**
         * Cartera vencida 10 primeros
         */
        $carteraVencida = $this->sells->mdlCarteraVencida($empresasID, $sucurssalesID);

        $carteraVencidaHTML = "";

        foreach ($carteraVencida as $key => $value) {



            $ruta = base_url("admin/listSells/1990-01-01/2100-01-01/false/0/0/$value[id]");

            $carteraVencidaHTML .= <<<EOF
                                    <li class="item">

                                          <div class="product-info">
                                              <a href="$ruta" class="product-title">$value[razonSocial]
                                                  <span class="badge badge-warning float-right">$$value[deuda]</span></a>
                                              <span class="product-description">
                                                 $value[razonSocial].
                                              </span>
                                          </div>
                                      </li>
                                EOF;
        }


      
        $datosExportar["carteraVencida"] = $carteraVencidaHTML;

        echo json_encode($datosExportar);
    }
}
