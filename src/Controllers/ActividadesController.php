<?php

namespace julio101290\boilerplateprojects\Controllers;

use App\Controllers\BaseController;
use julio101290\boilerplateprojects\Models\{
    ActividadesModel
};
use julio101290\boilerplatelog\Models\LogModel;
use CodeIgniter\API\ResponseTrait;
use julio101290\boilerplatecompanies\Models\EmpresasModel;
use julio101290\boilerplateprojects\Models\EtapasModel;
use julio101290\boilerplateprojects\Models\ProyectosModel;
use julio101290\boilerplateprojects\Models\ConceptosModel;
use julio101290\boilerplateunidadesmedidas\Models\{
    Unidades_medidaModel
};
use App\Models\UserModel;
use julio101290\boilerplateproducts\Models\ProductsModel;
use App\Controllers\LicenciasController;

//use julio101290\boilerplatelicences\LicenciasModel;


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
        //   $this->licencias = new LicenciasController();
        //  $this->licenciasModelo = new LicenciasModel();

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
            
            $request = service('request');

            // Respuesta JSON al estilo DataTables
            return $this->response->setJSON([
                        'draw' => (int) $request->getPost('draw'),
                        'recordsTotal' => 0,
                        'recordsFiltered' => 0,
                        'data' => [],
            ]);
        }
        $titulos["title"] = lang('actividades.title');
        $titulos["subtitle"] = lang('actividades.subtitle');
        return view('julio101290\boilerplateprojects\Views\actividades', $titulos);
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
            $request = service('request');

            $builder = $this->actividades->mdlGetActividades($empresasID);

            // DataTables parameters
            $search = $request->getPost('search')['value'] ?? '';
            $start = (int) $request->getPost('start') ?? 0;
            $length = (int) $request->getPost('length') ?? 10;
            $orderCol = $request->getPost('order')[0]['column'] ?? 0;
            $orderDir = $request->getPost('order')[0]['dir'] ?? 'asc';
            $columns = $request->getPost('columns') ?? [];

            // Column names (must match your frontend columns)
            $fields = [
                'a.id', 'a.idEmpresa', 'a.idProyecto', 'a.etapa', 'a.concepto',
                'a.descripcion', 'a.fechaInicio', 'a.fechaFinal', 'a.cantEstimada',
                'a.cantReal', 'a.unidadMedida', 'c.descripcion', 'd.descripcion',
                'e.descripcion', 'a.status', 'a.costoUnitario', 'a.costoTotalEstimado',
                'a.costoTotalReal', 'a.producto', 'a.created_at', 'a.updated_at',
                'a.deleted_at', 'b.nombre', 'f.descripcion', 'a.porcAvanzado'
            ];

            // Ordenar por columna seleccionada
            $orderBy = $fields[$orderCol] ?? 'a.id';
            $builder->orderBy($orderBy, $orderDir);

            // Búsqueda global
            if (!empty($search)) {
                $builder->groupStart();
                foreach ($fields as $field) {
                    $builder->orLike($field, $search);
                }
                $builder->groupEnd();
            }

            // Total sin filtros
            $totalRecords = $this->actividades->mdlGetActividades($empresasID)->countAllResults(false);

            // Total con filtros
            $filteredBuilder = clone $builder;
            $totalFiltered = $filteredBuilder->countAllResults();

            // Paginación
            $builder->limit($length, $start);

            // Obtener resultados
            $data = $builder->get()->getResult();

            // Respuesta JSON al estilo DataTables
            return $this->response->setJSON([
                        'draw' => (int) $request->getPost('draw'),
                        'recordsTotal' => $totalRecords,
                        'recordsFiltered' => $totalFiltered,
                        'data' => $data,
            ]);
        }

        $titulos[$caja] = $caja;
        $titulos["title"] = lang('actividades.title');
        $titulos["subtitle"] = lang('actividades.subtitle');
        return view('julio101290\boilerplateprojects\Views\actividades', $titulos);
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


            $request = service('request');

            $builder = $this->actividades->mdlGetActividadesFilters($empresasID, $desdeFecha, $hastaFecha, $pendientes, $empresa, $proyecto, $responsable);

            // DataTables parámetros
            $search = $request->getPost('search')['value'] ?? '';
            $start = (int) $request->getPost('start') ?? 0;
            $length = (int) $request->getPost('length') ?? 10;
            $orderCol = $request->getPost('order')[0]['column'] ?? 0;
            $orderDir = $request->getPost('order')[0]['dir'] ?? 'asc';

            // Columnas visibles en DataTable (ajusta según tus columnas)
            $fields = [
                'a.id', 'a.descripcion', 'c.descripcion', 'd.descripcion', 'e.descripcion',
                'a.fechaInicio', 'a.fechaFinal', 'a.status', 'a.porcAvanzado',
                'a.costoUnitario', 'a.costoTotalEstimado', 'a.producto', 'b.nombre',
                'f.descripcion', 'a.created_at', 'a.updated_at'
            ];
            $orderBy = $fields[$orderCol] ?? 'a.id';

            // Búsqueda general
            if (!empty($search)) {
                $builder->groupStart();
                foreach ($fields as $field) {
                    $builder->orLike($field, $search);
                }
                $builder->groupEnd();
            }

            // Conteo total sin filtro
            $base = $this->actividades->mdlGetActividadesFilters($empresasID, $desdeFecha, $hastaFecha, $pendientes, $empresa, $proyecto, $responsable);
            $totalRecords = $base->countAllResults(false); // evita reinicio del builder
            // Conteo con filtro aplicado
            $filteredBuilder = clone $builder;
            $totalFiltered = $filteredBuilder->countAllResults();

            // Orden y paginación
            $builder->orderBy($orderBy, $orderDir)
                    ->limit($length, $start);

            // Obtener datos
            $data = $builder->get()->getResult();

            // Respuesta tipo DataTables
            return $this->response->setJSON([
                        'draw' => (int) $request->getPost('draw'),
                        'recordsTotal' => $totalRecords,
                        'recordsFiltered' => $totalFiltered,
                        'data' => $data
            ]);
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
        /*
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
         */

        if ($datos["cantReal"] == "") {
            $datos["cantReal"] = "0";
        }
        
         if ($datos["cantReal"] == "") {
            $datos["cantReal"] = "0";
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
