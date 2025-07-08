<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use \App\Models\{
    ConceptosModel
};
use App\Models\LogModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\EmpresasModel;
use App\Models\Tipos_proyectoModel;
use App\Models\ProyectosModel;

class ConceptosController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $conceptos;
    protected $tiposProyecto;
    protected $proyectos;

    public function __construct() {
        $this->conceptos = new ConceptosModel();
        $this->log = new LogModel();
        $this->empresa = new EmpresasModel();
        $this->tiposProyecto = new Tipos_proyectoModel();
        $this->proyectos = new ProyectosModel();
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
            $datos = $this->conceptos->mdlGetConceptos($empresasID);

            return \Hermawan\DataTables\DataTable::of($datos)->toJson(true);
        }
        $titulos["title"] = lang('conceptos.title');
        $titulos["subtitle"] = lang('conceptos.subtitle');
        return view('conceptos', $titulos);
    }

    /**
     * Read Conceptos
     */
    public function getConceptos() {

        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        $idConceptos = $this->request->getPost("idConceptos");
        $datosConceptos = $this->conceptos->whereIn('idEmpresa', $empresasID)
                        ->where("id", $idConceptos)->first();

        if ($datosConceptos["tipoProyecto"] == null | $datosConceptos["tipoProyecto"] == "" || $datosConceptos["tipoProyecto"] == NULL || $datosConceptos["tipoProyecto"] == "NULL" || $datosConceptos["tipoProyecto"] == "null") {

            $datosConceptos["tipoProyecto"] = "0";
            $datosConceptos["descripcionTiposProyecto"] = "No se capturo el tipo de proyecto";
        } else {

            $tiposProyecto = $this->tiposProyecto->select("descripcion")->where("id", $datosConceptos["tipoProyecto"])->first();

            $datosConceptos["descripcionTiposProyecto"] = $tiposProyecto["descripcion"];
        }

        echo json_encode($datosConceptos);
    }

    /**
     * Save or update Conceptos
     */
    public function save() {
        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $datos = $this->request->getPost();
        if ($datos["idConceptos"] == 0) {
            try {
                if ($this->conceptos->save($datos) === false) {
                    $errores = $this->conceptos->errors();
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
            if ($this->conceptos->update($datos["idConceptos"], $datos) == false) {
                $errores = $this->conceptos->errors();
                foreach ($errores as $field => $error) {
                    echo $error . " ";
                }
                return;
            } else {
                $dateLog["description"] = lang("conceptos.logUpdated") . json_encode($datos);
                $dateLog["user"] = $userName;
                $this->log->save($dateLog);
                echo "Actualizado Correctamente";
                return;
            }
        }
        return;
    }

    /**
     * Delete Conceptos
     * @param type $id
     * @return type
     */
    public function delete($id) {
        $infoConceptos = $this->conceptos->find($id);
        helper('auth');
        $userName = user()->username;
        if (!$found = $this->conceptos->delete($id)) {
            return $this->failNotFound(lang('conceptos.msg.msg_get_fail'));
        }
        $this->conceptos->purgeDeleted();
        $logData["description"] = lang("conceptos.logDeleted") . json_encode($infoConceptos);
        $logData["user"] = $userName;
        $this->log->save($logData);
        return $this->respondDeleted($found, lang('conceptos.msg_delete'));
    }

    /**
     * Lista de tipos de Conceptos via AJax
     */
    public function getConceptosAjax() {

        $request = service('request');
        $postData = $request->getPost();

        $response = array();

        // Read new token and assign in $response['token']
        $response['token'] = csrf_hash();

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $idEmpresa = $postData['idEmpresa'];
        $conceptos = new ConceptosModel();

        if (!isset($postData['searchTerm'])) {
            // Fetch record

            $listConceptos = $conceptos->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("idEmpresa", $idEmpresa)
                    ->orderBy('id')
                    ->orderBy('idEmpresa')
                    ->orderBy('descripcion')
                    ->findAll();
        } else {
            $searchTerm = $postData['searchTerm'];

            $listConceptos = $conceptos->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("idEmpresa", $postData["idEmpresa"])
                    ->groupStart()
                    ->orLike('id', $searchTerm)
                    ->orLike('descripcion', $searchTerm)
                    ->groupEnd()
                    ->findAll();
        }

        $data = array();

        foreach ($listConceptos as $concepto) {
            $data[] = array(
                "id" => $concepto['id'],
                "text" => $concepto['id'] . ' ' . $concepto['descripcion'],
            );
        }

        $response['data'] = $data;

        return $this->response->setJSON($response);
    }

    /**
     * Lista de tipos de Conceptos via AJax
     */
    public function getConceptosActividadesAjax() {

        $request = service('request');
        $postData = $request->getPost();

        $response = array();

        // Read new token and assign in $response['token']
        $response['token'] = csrf_hash();

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $idEmpresa = $postData['idEmpresa'];
        $conceptos = new ConceptosModel();

        $datosProyecto = $this->proyectos->find($postData["idProyecto"]);

        if (!isset($postData['searchTerm'])) {
            // Fetch record

            $listConceptos = $conceptos->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("idEmpresa", $idEmpresa)
                    ->where("tipoProyecto", $datosProyecto["tipoProyecto"])
                    ->orderBy('id')
                    ->orderBy('idEmpresa')
                    ->orderBy('descripcion')
                    ->findAll();
        } else {
            $searchTerm = $postData['searchTerm'];

            $listConceptos = $conceptos->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("idEmpresa", $postData["idEmpresa"])
                    ->where("tipoProyecto", $datosProyecto["tipoProyecto"])
                    ->groupStart()
                    ->orLike('id', $searchTerm)
                    ->orLike('descripcion', $searchTerm)
                    ->groupEnd()
                    ->findAll();
        }

        $data = array();

        foreach ($listConceptos as $concepto) {
            $data[] = array(
                "id" => $concepto['id'],
                "text" => $concepto['id'] . ' ' . $concepto['descripcion'],
            );
        }

        $response['data'] = $data;

        return $this->response->setJSON($response);
    }
}
