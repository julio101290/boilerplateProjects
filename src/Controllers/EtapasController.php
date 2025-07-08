<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use \App\Models\{
    EtapasModel
};
use App\Models\LogModel;
use CodeIgniter\API\ResponseTrait;
use App\Models\EmpresasModel;
use App\Models\Tipos_proyectoModel;
use App\Models\ProyectosModel;

class EtapasController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $etapas;
    protected $tiposProyecto;
    protected $proyectos;

    public function __construct() {
        $this->etapas = new EtapasModel();
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
            $datos = $this->etapas->mdlGetEtapas($empresasID);

            return \Hermawan\DataTables\DataTable::of($datos)->toJson(true);
        }
        $titulos["title"] = lang('etapas.title');
        $titulos["subtitle"] = lang('etapas.subtitle');
        return view('etapas', $titulos);
    }

    /**
     * Read Etapas
     */
    public function getEtapas() {

        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        $idEtapas = $this->request->getPost("idEtapas");
        $datosEtapas = $this->etapas->whereIn('idEmpresa', $empresasID)
                        ->where("id", $idEtapas)->first();

        if ($datosEtapas["tipoProyecto"] == null || $datosEtapas["tipoProyecto"] == "" || $datosEtapas["tipoProyecto"] == NULL || $datosEtapas["tipoProyecto"] == "NULL" || $datosEtapas["tipoProyecto"] == "null") {

            $datosEtapas["tipoProyecto"] = "0";
            $datosEtapas["descripcionTiposProyecto"] = "No se capturo el tipo de proyecto";
        } else {

            $tiposProyecto = $this->tiposProyecto->select("descripcion")->where("id", $datosEtapas["tipoProyecto"])->first();

            $datosEtapas["descripcionTiposProyecto"] = $tiposProyecto["descripcion"];
        }


        echo json_encode($datosEtapas);
    }

    /**
     * Save or update Etapas
     */
    public function save() {
        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $datos = $this->request->getPost();
        if ($datos["idEtapas"] == 0) {
            try {
                if ($this->etapas->save($datos) === false) {
                    $errores = $this->etapas->errors();
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
            if ($this->etapas->update($datos["idEtapas"], $datos) == false) {
                $errores = $this->etapas->errors();
                foreach ($errores as $field => $error) {
                    echo $error . " ";
                }
                return;
            } else {
                $dateLog["description"] = lang("etapas.logUpdated") . json_encode($datos);
                $dateLog["user"] = $userName;
                $this->log->save($dateLog);
                echo "Actualizado Correctamente";
                return;
            }
        }
        return;
    }

    /**
     * Delete Etapas
     * @param type $id
     * @return type
     */
    public function delete($id) {
        $infoEtapas = $this->etapas->find($id);
        helper('auth');
        $userName = user()->username;
        if (!$found = $this->etapas->delete($id)) {
            return $this->failNotFound(lang('etapas.msg.msg_get_fail'));
        }
        $this->etapas->purgeDeleted();
        $logData["description"] = lang("etapas.logDeleted") . json_encode($infoEtapas);
        $logData["user"] = $userName;
        $this->log->save($logData);
        return $this->respondDeleted($found, lang('etapas.msg_delete'));
    }

    /**
     * Lista de tipos de Conceptos via AJax
     */
    public function getEtapasAjax() {

        $request = service('request');
        $postData = $request->getPost();

        $response = array();

        // Read new token and assign in $response['token']
        $response['token'] = csrf_hash();

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $idEmpresa = $postData['idEmpresa'];
        $etapas = new EtapasModel();

        if (!isset($postData['searchTerm'])) {
            // Fetch record

            $listEtapas = $etapas->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("idEmpresa", $idEmpresa)
                    ->orderBy('id')
                    ->orderBy('idEmpresa')
                    ->orderBy('descripcion')
                    ->findAll();
        } else {
            $searchTerm = $postData['searchTerm'];

            $listEtapas = $etapas->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("idEmpresa", $postData["idEmpresa"])
                    ->groupStart()
                    ->orLike('id', $searchTerm)
                    ->orLike('descripcion', $searchTerm)
                    ->groupEnd()
                    ->findAll();
        }

        $data = array();

        foreach ($listEtapas as $etapa) {
            $data[] = array(
                "id" => $etapa['id'],
                "text" => $etapa['id'] . ' ' . $etapa['descripcion'],
            );
        }

        $response['data'] = $data;

        return $this->response->setJSON($response);
    }

    /**
     * Lista de tipos de Conceptos via AJax Activdad
     */
    public function getEtapasAjaxActividad() {

        $request = service('request');
        $postData = $request->getPost();

        $response = array();

        // Read new token and assign in $response['token']
        $response['token'] = csrf_hash();

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $idEmpresa = $postData['idEmpresa'];
        $etapas = new EtapasModel();

        $proyectosDatos = $this->proyectos->find($postData["idProyecto"]);

        if (!isset($postData['searchTerm'])) {
            // Fetch record

            $listEtapas = $etapas->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("idEmpresa", $idEmpresa)
                    ->where("tipoProyecto", $proyectosDatos["tipoProyecto"])
                    ->orderBy('id')
                    ->orderBy('idEmpresa')
                    ->orderBy('descripcion')
                    ->findAll();
        } else {
            $searchTerm = $postData['searchTerm'];

            $listEtapas = $etapas->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("tipoProyecto", $proyectosDatos["tipoProyecto"])
                    ->where("idEmpresa", $postData["idEmpresa"])
                    ->groupStart()
                    ->orLike('id', $searchTerm)
                    ->orLike('descripcion', $searchTerm)
                    ->groupEnd()
                    ->findAll();
        }

        $data = array();

        foreach ($listEtapas as $etapa) {
            $data[] = array(
                "id" => $etapa['id'],
                "text" => $etapa['id'] . ' ' . $etapa['descripcion'],
            );
        }

        $response['data'] = $data;

        return $this->response->setJSON($response);
    }
}
