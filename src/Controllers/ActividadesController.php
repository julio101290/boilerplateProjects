<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use \App\Models\{
    ActividadesModel
};
use App\Models\LogModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\EmpresasModel;
use App\Models\EtapasModel;
use App\Models\ProyectosModel;
use App\Models\ConceptosModel;
use App\Models\Unidades_medidaModel;
use App\Models\UserModel;
use App\Models\ProductsModel;
use App\Controllers\LicenciasController;
use App\Models\LicenciasModel;

class ActividadesController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $actividades;
    protected $proyectos;
    protected $etapas;
    protected $conceptos;
    protected $unidadesMedida;
    protected $usuarios;
    protected $productos;
    protected $licencias;
    protected $licenciasModelo;
    protected $empresa;

    public function __construct() {
        $this->actividades = new ActividadesModel();
        $this->log = new LogModel();
        $this->empresa = new EmpresasModel();
        $this->proyectos = new ProyectosModel();
        $this->etapas = new EtapasModel();
        $this->conceptos = new ConceptosModel();
        $this->unidadesMedida = new Unidades_medidaModel();
        $this->usuarios = new UserModel();
        $this->productos = new ProductsModel();
        $this->licencias = new LicenciasController();
        $this->licenciasModelo = new LicenciasModel();

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
            $datos = $this->actividades->mdlGetActividades($empresasID);

            return \Hermawan\DataTables\DataTable::of($datos)->toJson(true);
        }
        $titulos["title"] = lang('actividades.title');
        $titulos["subtitle"] = lang('actividades.subtitle');
        return view('actividades', $titulos);
    }
    
    
    
    /**
     * 
     * Actividades desde caja
     * 
     */
    
        public function actividadesDesdeCaja($caja) {



        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }




        if ($this->request->isAJAX()) {
            $datos = $this->actividades->mdlGetActividades($empresasID);

            return \Hermawan\DataTables\DataTable::of($datos)->toJson(true);
        }
        
        $titulos[$caja] = $caja;
        $titulos["title"] = lang('actividades.title');
        $titulos["subtitle"] = lang('actividades.subtitle');
        return view('actividades', $titulos);
    }

    /**
     * Read Actividades
     */
    public function getActividades() {

        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        $idActividades = $this->request->getPost("idActividades");
        $datosActividades = $this->actividades->whereIn('idEmpresa', $empresasID)
                        ->where("id", $idActividades)->first();

        $datosConcepto = $this->conceptos->find($datosActividades["concepto"]);
        $datosActividades["nombreConcepto"] = $datosConcepto["descripcion"];

        $datosProyecto = $this->proyectos->find($datosActividades["idProyecto"]);
        $datosActividades["nombreProyecto"] = $datosProyecto["descripcion"];

        $datosEtapa = $this->etapas->find($datosActividades["etapa"]);
        $datosActividades["nombreEtapa"] = $datosEtapa["descripcion"];

        $datosUnidades = $this->unidadesMedida->find($datosActividades["unidadMedida"]);
        $datosActividades["nombreUnidadMedida"] = $datosUnidades["descripcion"];

        if ($datosActividades["idUsuario"] == 0) {

            $datosActividades["nombreUsuario"] = "Seleccione usuario";
        } else {



            $datosUsuario = $this->usuarios->mdlGetUser($datosActividades["idUsuario"]);

            $datosUsuario = $datosUsuario["0"];

            $datosActividades["nombreUsuario"] = $datosUsuario["username"] . " " . $datosUsuario["firstname"] . " " . $datosUsuario["lastname"];
        }


        if ($datosActividades["producto"] == 0 || $datosActividades["producto"] == '' || $datosActividades["producto"] == 'NULL' || $datosActividades["producto"] == 'null' || $datosActividades["producto"] == 'Null'
        ) {

            $datosActividades["nombreProducto"] = "Seleccione Producto";
        } else {



            $datosProducto = $this->productos->find($datosActividades["producto"]);
         

            $datosActividades["nombreProducto"] = $datosProducto["description"];
        }




        echo json_encode($datosActividades);
    }

    /**
     * Actividades por filtro
     * @param type $desdeFecha
     * @param type $hastaFecha
     * @param type $todas
     * @param type $empresa
     * @param type $sucursal
     * @param type $cliente
     * @return type
     */
    public function actividadesFilters($desdeFecha, $hastaFecha, $pendientes, $empresa, $proyecto, $responsable) {


        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }


        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        if ($this->request->isAJAX()) {


            $datos = $this->actividades->mdlGetActividadesFilters($empresasID, $desdeFecha, $hastaFecha, $pendientes, $empresa, $proyecto, $responsable);

            return \Hermawan\DataTables\DataTable::of($datos)->toJson(true);
        }
    }

    /**
     * Actividades por filtro
     * @param type $desdeFecha
     * @param type $hastaFecha
     * @param type $todas
     * @param type $empresa
     * @param type $sucursal
     * @param type $cliente
     * @return type
     */
    public function actividadesCuboFilters($desdeFecha, $hastaFecha, $pendientes, $empresa, $proyecto, $responsable) {


        $auth = service('authentication');
        if (!$auth->check()) {

            return redirect()->route('admin');
        }


        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        if ($this->request->isAJAX()) {


            $datos = $this->actividades->mdlGetActividadesFiltersCubo($empresasID, $desdeFecha, $hastaFecha, $pendientes, $empresa, $proyecto, $responsable);

            return json_encode($datos);
        }
    }

    /**
     * Save or update Actividades
     */
    public function save() {
        helper('auth');
        helper('utilerias_helper');
        $userName = user()->username;
        $idUser = user()->id;
        $datos = $this->request->getPost();
        
        /**
         * Generamos cadena
         */
        $datosEmpresa = $this->empresa->find($datos["idEmpresa"]);
        
        
        $licenciaDisponible = $this->licenciasModelo->mdlObtenerLicencia(fechaActual(),"PROY");
        
        if(count($licenciaDisponible)>0){
            
            $licenciaDisponible = $licenciaDisponible[0];
              
             $cadena = $datosEmpresa["nombre"].$datosEmpresa["rfc"]."PROY".$licenciaDisponible["desdeFecha"].$licenciaDisponible["hastaFecha"]."DegreeLessnessMode_On";
            
        }else{
            
             $cadena = $datosEmpresa["nombre"].$datosEmpresa["rfc"]."PROY".fechaActualGuion();
            
        }
  
       
        
        $validaLicencia = $this->licencias->ctrValidarLicencia($cadena, fechaActualGuion(), "PROY");
        
        if(!$validaLicencia){
            
            $totalRegistros = $this->actividades->mdlTotalRegistros( fechaActualRestarDias(30),fechaActual());
            
            $totalRegistros= $totalRegistros[0]["totalRegistros"];
            
            $maxRegistros =10;
            if($totalRegistros>$maxRegistros){
                
                echo "Solo se permiten $maxRegistros registros en los ultimo 30 en la version demo";
                return;
            }
            
        }
        
        if ($datos["idActividades"] == 0) {
            try {
                if ($this->actividades->save($datos) === false) {
                    $errores = $this->actividades->errors();
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
            if ($this->actividades->update($datos["idActividades"], $datos) == false) {
                $errores = $this->actividades->errors();
                foreach ($errores as $field => $error) {
                    echo $error . " ";
                }
                return;
            } else {
                $dateLog["description"] = lang("actividades.logUpdated") . json_encode($datos);
                $dateLog["user"] = $userName;
                $this->log->save($dateLog);
                echo "Actualizado Correctamente";
                return;
            }
        }
        return;
    }

    /**
     * Delete Actividades
     * @param type $id
     * @return type
     */
    public function delete($id) {
        $infoActividades = $this->actividades->find($id);
        helper('auth');
        $userName = user()->username;
        if (!$found = $this->actividades->delete($id)) {
            return $this->failNotFound(lang('actividades.msg.msg_get_fail'));
        }
        $this->actividades->purgeDeleted();
        $logData["description"] = lang("actividades.logDeleted") . json_encode($infoActividades);
        $logData["user"] = $userName;
        $this->log->save($logData);
        return $this->respondDeleted($found, lang('actividades.msg_delete'));
    }

    /**
     * Get Custumers via AJax
     */
    public function getUsuariosAjax() {

        $request = service('request');
        $postData = $request->getPost();

        $response = array();

        // Read new token and assign in $response['token']
        $response['token'] = csrf_hash();
        $custumers = new CustumersModel();
        $idEmpresa = $postData['idEmpresa'];

        if (!isset($postData['searchTerm'])) {
            // Fetch record

            $listCustumers = $custumers->select('id,firstname,lastname')->where("deleted_at", null)
                    ->where('idEmpresa', $idEmpresa)
                    ->orderBy('id')
                    ->orderBy('firstname')
                    ->orderBy('lastname')
                    ->findAll(10);
        } else {
            $searchTerm = $postData['searchTerm'];

            // Fetch record

            $listCustumers = $custumers->select('id,firstname,lastname')->where("deleted_at", null)
                    ->where('idEmpresa', $idEmpresa)
                    ->groupStart()
                    ->like('firstname', $searchTerm)
                    ->orLike('id', $searchTerm)
                    ->orLike('lastname', $searchTerm)
                    ->groupEnd()
                    ->findAll(10);
        }

        $data = array();
        foreach ($listCustumers as $custumers) {
            $data[] = array(
                "id" => $custumers['id'],
                "text" => $custumers['id'] . ' ' . $custumers['firstname'] . ' ' . $custumers['lastname'],
            );
        }

        $response['data'] = $data;

        return $this->response->setJSON($response);
    }

    /**
     * Obtenemos usuarios por empresa 
     */
    public function getCustumersTodosAjax() {

        $request = service('request');
        $postData = $request->getPost();

        $response = array();

        // Read new token and assign in $response['token']
        $response['token'] = csrf_hash();
        $custumers = new CustumersModel();
        $idEmpresa = $postData['idEmpresa'];

        if (!isset($postData['searchTerm'])) {
            // Fetch record

            $listCustumers = $custumers->select('id,firstname,lastname')->where("deleted_at", null)
                    ->where('idEmpresa', $idEmpresa)
                    ->orderBy('id')
                    ->orderBy('firstname')
                    ->orderBy('lastname')
                    ->findAll(10);
        } else {
            $searchTerm = $postData['searchTerm'];

            // Fetch record

            $listCustumers = $custumers->select('id,firstname,lastname')->where("deleted_at", null)
                    ->where('idEmpresa', $idEmpresa)
                    ->groupStart()
                    ->like('firstname', $searchTerm)
                    ->orLike('id', $searchTerm)
                    ->orLike('lastname', $searchTerm)
                    ->groupEnd()
                    ->findAll(10);
        }

        $data = array();
        $data[] = array(
            "id" => 0,
            "text" => "0 Todos los clientes",
        );
        foreach ($listCustumers as $custumers) {
            $data[] = array(
                "id" => $custumers['id'],
                "text" => $custumers['id'] . ' ' . $custumers['firstname'] . ' ' . $custumers['lastname'],
            );
        }

        $response['data'] = $data;

        return $this->response->setJSON($response);
    }
}
