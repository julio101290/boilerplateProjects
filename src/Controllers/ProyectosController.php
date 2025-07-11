<?php

namespace julio101290\boilerplateprojects\Controllers;

use App\Controllers\BaseController;
use julio101290\boilerplateprojects\Models\{
    ProyectosModel
};
use julio101290\boilerplatelog\Models\LogModel;
use CodeIgniter\API\ResponseTrait;
use julio101290\boilerplatecompanies\Models\EmpresasModel;
use julio101290\boilerplatebranchoffice\Models\BranchofficesModel;
use julio101290\boilerplatecustumers\Models\CustumersModel;
use julio101290\boilerplateprojects\Models\Tipos_proyectoModel;
use App\Models\UserModel;

class ProyectosController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $proyectos;
    protected $sucursales;
    protected $clientes;
    protected $tiposProyecto;
    protected $usuarios;

    public function __construct() {
        $this->proyectos = new ProyectosModel();
        $this->log = new LogModel();
        $this->empresa = new EmpresasModel();
        $this->sucursales = new BranchofficesModel();
        $this->clientes = new CustumersModel();
        $this->tiposProyecto = new Tipos_proyectoModel();
        $this->usuarios = new UserModel();

        helper('menu');
        helper('utilerias');
    }

    public function index() {



        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }




        if ($this->request->isAJAX()) {
            $draw = (int) $this->request->getGet('draw');
            $start = (int) $this->request->getGet('start');
            $length = (int) $this->request->getGet('length');
            $search = $this->request->getGet('search')['value'] ?? '';
            $orderIndex = (int) $this->request->getGet('order')[0]['column'] ?? 0;
            $orderDir = $this->request->getGet('order')[0]['dir'] ?? 'asc';

            $columns = [
                'a.id',
                'a.descripcion',
                'c.name',
                'd.descripcion',
                'e.firstname',
                'f.firstname',
                'a.fechaInicio'
            ];
            $orderBy = $columns[$orderIndex] ?? 'a.id';

            $params = [
                'start' => $start,
                'length' => $length,
                'search' => $search,
                'orderBy' => $orderBy,
                'orderDir' => $orderDir,
            ];

            $resultado = $this->proyectos->mdlGetProyectos($empresasID, $params);

            return $this->response->setJSON([
                        'draw' => $draw,
                        'recordsTotal' => $resultado['total'],
                        'recordsFiltered' => $resultado['filtered'],
                        'data' => $resultado['data'],
            ]);
        }
        $titulos["title"] = lang('proyectos.title');
        $titulos["subtitle"] = lang('proyectos.subtitle');
        return view('julio101290\boilerplateprojects\Views\proyectos', $titulos);
    }

    public function ctrCubo() {



        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }



        /*
          if ($this->request->isAJAX()) {
          $datos = $this->proyectos->mdlGetProyectos($empresasID);

          return \Hermawan\DataTables\DataTable::of($datos)->toJson(true);
          }

         */
        $titulos["title"] = "Cubo";
        $titulos["subtitle"] = "Tabla dinamica";
        return view('julio101290\boilerplateprojects\Views\cubo', $titulos);
    }

    /**
     * Read Proyectos
     */
    public function getProyectos() {

        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        $idProyectos = $this->request->getPost("idProyectos");
        $datosProyectos = $this->proyectos->mdlGetProyecto($empresasID, $idProyectos);

        $sucursal = $this->sucursales->select("*")->where("id", $datosProyectos["idSucursal"])->first();

        $datosCliente = $this->clientes->select("firstname,lastname")->where("id", $datosProyectos["idCliente"])->first();

        $tiposProyecto = $this->tiposProyecto->select("descripcion")->where("id", $datosProyectos["tipoProyecto"])->first();

        $datosProyectos["nombreCliente"] = $datosCliente["firstname"] . " " . $datosCliente["lastname"];

        $datosProyectos["nombreSucursal"] = $sucursal["name"];

        $datosProyectos["descripcionTiposProyecto"] = $tiposProyecto["descripcion"];

        if ($datosProyectos["responsable"] == 0) {

            $datosActividades["nombreUsuario"] = "Seleccione usuario";
        } else {



            $datosUsuario = $this->usuarios->mdlGetUser($datosProyectos["responsable"]);

            $datosUsuario = $datosUsuario["0"];

            $datosProyectos["nombreUsuario"] = $datosUsuario["username"] . " " . $datosUsuario["firstname"] . " " . $datosUsuario["lastname"];
        }

        echo json_encode($datosProyectos);
    }

    /**
     * Save or update Proyectos
     */
    public function save() {
        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $datos = $this->request->getPost();
        if ($datos["idProyectos"] == 0) {
            try {
                if ($this->proyectos->save($datos) === false) {
                    $errores = $this->proyectos->errors();
                    foreach ($errores as $field => $error) {
                        echo $error . " ";
                    }
                    return;
                }
                $dateLog["description"] = lang("vehicles.logDescription") . json_encode($datos);
                $dateLog["user"] = $userName;
                $this->log->save($dateLog);
                echo "Guardado Correctamente";
            } catch (\PHPUnit\Framework\Exception $ex) {
                echo "Error al guardar " . $ex->getMessage();
            }
        } else {
            if ($this->proyectos->update($datos["idProyectos"], $datos) == false) {
                $errores = $this->proyectos->errors();
                foreach ($errores as $field => $error) {
                    echo $error . " ";
                }
                return;
            } else {
                $dateLog["description"] = lang("proyectos.logUpdated") . json_encode($datos);
                $dateLog["user"] = $userName;
                $this->log->save($dateLog);
                echo "Actualizado Correctamente";
                return;
            }
        }
        return;
    }

    /**
     * Delete Proyectos
     * @param type $id
     * @return type
     */
    public function delete($id) {
        $infoProyectos = $this->proyectos->find($id);
        helper('auth');
        $userName = user()->username;
        if (!$found = $this->proyectos->delete($id)) {
            return $this->failNotFound(lang('proyectos.msg.msg_get_fail'));
        }
        $this->proyectos->purgeDeleted();
        $logData["description"] = lang("proyectos.logDeleted") . json_encode($infoProyectos);
        $logData["user"] = $userName;
        $this->log->save($logData);
        return $this->respondDeleted($found, lang('proyectos.msg_delete'));
    }

    /**
     * Lista de tipos de Proyectos via AJax
     */
    public function getProyectosAjax() {

        $request = service('request');
        $postData = $request->getPost();

        $response = array();

        // Read new token and assign in $response['token']
        $response['token'] = csrf_hash();

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $idEmpresa = $postData['idEmpresa'];
        $proyectos = new ProyectosModel();

        if (!isset($postData['searchTerm'])) {
            // Fetch record

            $listProyectos = $proyectos->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("idEmpresa", $idEmpresa)
                    ->orderBy('id')
                    ->orderBy('idEmpresa')
                    ->orderBy('descripcion')
                    ->findAll();
        } else {
            $searchTerm = $postData['searchTerm'];

            $listProyectos = $proyectos->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("idEmpresa", $postData["idEmpresa"])
                    ->groupStart()
                    ->orLike('id', $searchTerm)
                    ->orLike('descripcion', $searchTerm)
                    ->groupEnd()
                    ->findAll();
        }

        $data = array();

        foreach ($listProyectos as $proyecto) {
            $data[] = array(
                "id" => $proyecto['id'],
                "text" => $proyecto['id'] . ' ' . $proyecto['descripcion'],
            );
        }

        $response['data'] = $data;

        return $this->response->setJSON($response);
    }

    public function report($id, $isMail = 0) {


        $pdf = new PDFLayoutProyecto(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);

        $datosProyecto = $this->proyectos->where("id", $id)->first();

        $db = db_connect();
        
        $responsable = $db->table('users')
                ->select('id,email,username,firstname,lastname')
                ->where('id', $datosProyecto["responsable"])
                ->get()
                ->getResultArray();

        if (isset($responsable[0])) {

            $responsable = $responsable[0];
            $pdf->nombreResponsable = $responsable["firstname"] . " " . $responsable["lastname"];
        } else {

            $pdf->nombreResponsable = "Responsable no seleccionado";
        }



        $resumenProyecto = $this->proyectos->mdlResumenProyecto($id);

        $presupuesto = $this->proyectos->mdlPresupuestoProyecto($id);

        $datosEmpresa = $this->empresa->select("*")->where("id", $datosProyecto["idEmpresa"])->first();
        $datosEmpresaObj = $this->empresa->select("*")->where("id", $datosProyecto["idEmpresa"])->asObject()->first();

        $pdf->nombreDocumento = "Proyecto";
        $pdf->direccion = $datosEmpresaObj->direccion;
        $pdf->telefono = $datosEmpresaObj->telefono;

        if ($datosEmpresaObj->logo == NULL || $datosEmpresaObj->logo == "") {

            $pdf->logo = ROOTPATH . "public/images/logo/default.png";
        } else {

            $pdf->logo = ROOTPATH . "public/images/logo/" . $datosEmpresaObj->logo;
        }
        // $pdf->folio = str_pad($datosProyecto["id"], 5, "0", STR_PAD_LEFT);
        // set document information
        $pdf->nombreEmpresa = $datosEmpresa["nombre"];
        $pdf->direccion = $datosEmpresa["direccion"];
        $pdf->usuario = ""; //  $user["firstname"] . " " . $user["lastname"];
        $pdf->nombreProyecto = $datosProyecto["descripcion"]; //  $user["firstname"] . " " . $user["lastname"];
        $pdf->SetCreator(PDF_CREATOR);
        $pdf->SetAuthor("asd");
        $pdf->SetTitle('CI4JCPOS');
        $pdf->SetSubject('CI4JCPOS');
        $pdf->SetKeywords('CI4JCPOS, PDF, PHP, CodeIgniter, CESARSYSTEMS.COM.MX');

        // set default header data
        $pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

        // set header and footer fonts
        $pdf->setHeaderFont(array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
        $pdf->setFooterFont(array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, 50, PDF_MARGIN_RIGHT);
        $pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
        $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM + 50);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

        $pdf->AddPage();

        /**
         * IMPRIMIMOS EL RESUMEN
         */
        //$pdf->SetFont('dejavusans', 'B', 10);
        $pdf->writeHTML("RESUMEN", true, false, false, false, 'L');

        $bloque3 = <<<EOF
                        <br>
                        <br>
                <table style="font-size:10px; padding:5px 10px; font-weight: bold;">
                    <tr style="border-bottom:1pt solid black;">


                    <td style="height: 20px;line-height:10px; width: 525px;  padding: 4px 4px 4px; font-weight:bold;  text-align:left; border-left: 0px;  border-top: 0px;  border-bottom: 0px;"> &nbsp;<br>ETAPA &nbsp;<br></td>
                    <td style="height: 20px;line-height:10px; width: 120px;  padding: 4px 4px 4px; font-weight:bold;  text-align:center; border-top: 0px;  border-bottom: 0px; border-right: 0px;">&nbsp;<br>TOTAL&nbsp;<br></td>

                    </tr>

                </table>
    <br>
    EOF;

        $pdf->writeHTML($bloque3, false, false, false, false, '');
        $importeEtapaResumen = 0;

        $importeEtapaResumenTotal = 0;
        foreach ($resumenProyecto as $key => $value) {

            $clase = 'style="  padding: 3px 4px 3px; ';

            $importeEtapaResumen = number_format($value["costoTotalEstimado"], 2, ".", ",");
            $bloque4 = <<<EOF
                            <table style="font-size:12px; padding:5px 10px;">

                                <tr>
                                    
                           
                        
                                    <td  $clase width:525px; text-align:left" >
                                        $value[descripcionEtapa]
                                    </td>
                            
                                    <td $clase  width:120px; text-align:right" >
                                        $$importeEtapaResumen
                                    </td>

                                </tr>

                            </table>


                        EOF;

            $importeEtapaResumenTotal = $importeEtapaResumenTotal + $value["costoTotalEstimado"];
            $pdf->writeHTML($bloque4, false, false, false, false, '');
        }

        $clase = 'style="  padding: 3px 4px 3px; ';
        $importeEtapaResumenTotal = number_format($importeEtapaResumenTotal, 2, ".", ",");
        $bloque4 = <<<EOF
                            <table style="font-size:12px; padding:5px 10px;  font-weight: bold;">

                                <tr>
                                    
                           
                        
                                    <td  $clase width:525px; text-align:left" >
                                        TOTAL PROYECTO:
                                    </td>
                            
                                    <td $clase  width:120px; text-align:right" >
                                        $$importeEtapaResumenTotal
                                    </td>

                                </tr>

                            </table>


                        EOF;

        $pdf->writeHTML($bloque4, false, false, false, false, '');

        $pdf->AddPage();

        /*         * Imprimimos encabezado 
         */
        $bloque3 = <<<EOF

        <table style="font-size:10px; padding:5px 10px;"  >
            <tr style="border-bottom:1pt solid black;">
    
            
            <td style="height: 20px;line-height:10px; width: 100px;  padding: 4px 4px 4px; font-weight:bold;  text-align:left; border-left: 0px;  border-top: 0px;  border-bottom: 0px;"> &nbsp;<br>ETAPA &nbsp;<br></td>
           
   
            <td style="height: 20px;line-height:10px; width: 100px; padding: 4px 4px 4px; font-weight:bold; text-align:left; border-top: 0px;  border-bottom: 0px; ">&nbsp;<br>CONCEPTO&nbsp;<br></td>
    
            <td style="height: 20px;line-height:10px; width: 100px; padding: 4px 4px 4px; font-weight:bold;  text-align:center; border-top: 0px;  border-bottom: 0px;">&nbsp;<br>DESCRIPCION&nbsp;<br></td>
            <td style="height: 20px;line-height:10px; width: 100px; padding: 4px 4px 4px; font-weight:bold;  text-align:center; border-top: 0px;  border-bottom: 0px;">&nbsp;<br>CANTIDAD&nbsp;<br></td>
            <td style="height: 20px;line-height:10px; width: 70px; padding: 4px 4px 4px; font-weight:bold; text-align:center; border-top: 0px;  border-bottom: 0px;">&nbsp;<br>UNIDAD&nbsp;<br></td>
            <td style="height: 20px;line-height:10px; width: 100px;  padding: 4px 4px 4px; font-weight:bold;  text-align:center; border-top: 0px;  border-bottom: 0px;">&nbsp;<br>PRECIO UNITARIO&nbsp;<br></td>
            <td style="height: 20px;line-height:10px; width: 100px;  padding: 4px 4px 4px; font-weight:bold;  text-align:center; border-top: 0px;  border-bottom: 0px; border-right: 0px;">&nbsp;<br>TOTAL&nbsp;<br></td>
    
            </tr>
 
        </table>
    
    EOF;

        $pdf->writeHTML($bloque3, false, false, false, false, '');

        /**
         * Imprimimos detalle
         */
        $contador = 0;
        $contadorEtapa = 1;
        $contadorConcepto = 0;
        $nombreEtapa = "";
        $nombreConcepto = "";

        $totalEtapa = 0;
        $totalConcepto = 0;
        $totalProyecto = 0;

        foreach ($presupuesto as $key => $value) {



            if ($contador % 2 == 0) {
                $clase = 'style="  padding: 3px 4px 3px; ';
            } else {
                $clase = 'style=" padding: 3px 4px 3px; ';
            }


            if ($nombreEtapa != $value["descripcionEtapa"]) {

                if ($nombreEtapa != "") {

                    if ($nombreConcepto != "") {

                        $totalConceptoImpresion = number_format($totalConcepto, 2, ".", ",");

                        $bloque4 = <<<EOF
                            <table style="font-size:12px; padding:5px 10px;  font-weight: bold;">

                                <tr>
                                    
                                    <td  $clase width:50px; text-align:left" colspan="6">
                                        
                                    </td>
                        
                                    <td  $clase width:535px; text-align:left" >
                                        Total Concepto: $nombreConcepto
                                    </td>
                            
                                    <td  $clase width:100px; text-align:right" >
                                        $$totalConceptoImpresion
                                    </td>

                                </tr>

                            </table>


                        EOF;

                        $totalConcepto = 0;
                        $contador++;
                        $pdf->writeHTML($bloque4, false, false, false, false, '');
                    }

                    $totalEtapa = number_format($totalEtapa, 2, ".", ",");

                    $bloque4 = <<<EOF
                                   &nbsp;<br>
                            <table style="font-size:14px; padding:5px 10px;   font-weight: bold;">

                                <tr>

                                    <td  $clase width:535px; text-align:left; border-top: 0px; border-bottom: 0px;">
                                       Total Etapa:  $nombreEtapa
                                    </td>
                                   
                                    <td  $clase width:130px; text-align:right; border-top: 0px; border-bottom: 0px;">
                                      $$totalEtapa
                                    </td>

                                </tr>

                            </table>


                        EOF;
                    $contador++;
                    $pdf->writeHTML($bloque4, false, false, false, false, '');

                    $totalEtapa = 0.00;
                }


                $nombreEtapa = $value["descripcionEtapa"];

                $nombreConcepto = "";

                $bloque4 = <<<EOF
    
                            <table style="font-size:14px; padding:5px 10px;  font-weight: bold;">

                                <tr>

                                    <td  $clase width:670px; text-align:left" colspan="6">
                                        $value[descripcionEtapa]
                                    </td>

                                </tr>

                            </table>


                        EOF;
                $contador++;
                $pdf->writeHTML($bloque4, false, false, false, false, '');
            }

            if ($nombreConcepto != $value["descripcionConcepto"]) {


                if ($nombreConcepto != "") {

                    $totalConceptoImpresion = number_format($totalConcepto, 2, ".", ",");
                    $bloque4 = <<<EOF
                            <table style="font-size:12px; padding:5px 10px;  font-weight: bold;">

                                <tr>
                                    
                                    <td  $clase width:50px; text-align:left" colspan="6">
                                        
                                    </td>
                        
                                    <td  $clase width:505px; text-align:left" >
                                        Total Concepto: $nombreConcepto
                                    </td>
                            
                                    <td  $clase width:130px; text-align:right" >
                                        $$totalConceptoImpresion
                                    </td>

                                </tr>

                            </table>


                        EOF;

                    $totalConcepto = 0;
                    $contador++;
                    $pdf->writeHTML($bloque4, false, false, false, false, '');
                }

                $nombreConcepto = $value["descripcionConcepto"];

                $bloque4 = <<<EOF
    
                            <table style="font-size:12px; padding:5px 10px;  font-weight: bold;">

                                <tr>
                                    
                                    <td  $clase width:50px; text-align:left" colspan="6">
                                        
                                    </td>
                        
                                    <td  $clase width:670px; text-align:left" colspan="6">
                                        $value[descripcionConcepto]
                                    </td>

                                </tr>

                            </table>


                        EOF;
                $contador++;
                $pdf->writeHTML($bloque4, false, false, false, false, '');
            }

            $costoUnitario = number_format($value["costoUnitario"], 2, ".", ",");

            $costoTotalEstimado = number_format($value["costoTotalEstimado"], 2, ".", ",");
            $bloque4 = <<<EOF
    
        <table style="font-size:10px;">
    
            <tr>
    
                <td  $clase width:50px; text-align:left">
                  
                </td>
    
    
                <td  $clase width:50spx; text-align:left">
                   
                </td>
    
             
                <td $clase width:200px; text-align:left">
                $value[descripcionActividad]
                </td>
                    
                    <td $clase width:100px; text-align:right">
                    $value[cantEstimada]
                </td>
                
                <td $clase width:70px; text-align:center">
                    $value[descripcionUnidadMedida]
                </td>
    
                <td $clase width:100px; text-align:center">
                $$costoUnitario
                </td>
    
                <td $clase width:100px; text-align:right">
                $$costoTotalEstimado
                </td>
    
               
    
    
            </tr>
    
        </table>
    
    
    EOF;
            $contador++;
            $pdf->writeHTML($bloque4, false, false, false, false, '');

            $totalEtapa = $totalEtapa + $value["costoTotalEstimado"];
            $totalConcepto = $totalConcepto + $value["costoTotalEstimado"];
            $totalProyecto = $totalProyecto + $value["costoTotalEstimado"];
        }


        /**
         * TOTAL ULTIMO CONCEPTO
         */
        $totalConcepto = number_format($totalConcepto, 2, ".", ",");
        $bloque4 = <<<EOF
                            <table style="font-size:12px; padding:5px 10px;  font-weight: bold;">

                                <tr>
                                    
                                    <td  $clase width:50px; text-align:left" colspan="6">
                                        
                                    </td>
                        
                                    <td  $clase width:535px; text-align:left" >
                                        Total Concepto: $nombreConcepto
                                    </td>
                            
                                    <td  $clase width:100px; text-align:right" >
                                        $$totalConcepto
                                    </td>

                                </tr>

                            </table>


                        EOF;

        $totalConcepto = 0;
        $contador++;
        $pdf->writeHTML($bloque4, false, false, false, false, '');

        /**
         * Total Ultima Etapa
         */
        $totalEtapa = number_format($totalEtapa, 2, ".", ",");

        $bloque4 = <<<EOF
                                   &nbsp;<br>
                            <table style="font-size:14px; padding:5px 10px;   font-weight: bold;">

                                <tr>

                                    <td  $clase width:565px; text-align:left; border-top: 0px; border-bottom: 0px;">
                                       Total Etapa:  $nombreEtapa
                                    </td>
                                   
                                    <td  $clase width:100px; text-align:right; border-top: 0px; border-bottom: 0px;">
                                      $$totalEtapa
                                    </td>

                                </tr>

                            </table>


                        EOF;
        $contador++;
        $pdf->writeHTML($bloque4, false, false, false, false, '');

        /**
         * TOTAL PROYECTO
         */
        /**
         * Total Ultima Etapa
         */
        $totalProyecto = number_format($totalProyecto, 2, ".", ",");
        $bloque4 = <<<EOF
                                   &nbsp;<br>
                                   &nbsp;<br>
                                   &nbsp;<br>
                            <table style="font-size:14px; padding:5px 10px;   font-weight: bold;">

                                <tr>

                                    <td  $clase width:565px; text-align:left; border-top: 0px; border-bottom: 0px;">
                                       TOTAL PROYECTO:  $datosProyecto[descripcion]
                                    </td>
                                   
                                    <td  $clase width:100px; text-align:right; border-top: 0px; border-bottom: 0px;">
                                      $$totalProyecto
                                    </td>

                                </tr>

                            </table>


                        EOF;
        $contador++;
        $pdf->writeHTML($bloque4, false, false, false, false, '');

        if ($isMail == 0) {
            ob_end_clean();
            $this->response->setHeader("Content-Type", "application/pdf");
            $pdf->Output('Presupuesto.pdf', 'I');
        } else {

            $attachment = $pdf->Output('proyecto.pdf', 'S');

            return $attachment;
        }


        //============================================================+
        // END OF FILE
        //============================================================+
    }
}
