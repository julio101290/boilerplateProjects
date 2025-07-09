<?php

namespace julio101290\boilerplateprojects\Controllers;

use App\Controllers\BaseController;
use julio101290\boilerplateprojects\Models\{
    Tipos_proyectoModel
};
use julio101290\boilerplatelog\Models\LogModel;
use CodeIgniter\API\ResponseTrait;
use julio101290\boilerplatecompanies\Models\EmpresasModel;

class Tipos_proyectoController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $tipos_proyecto;

    public function __construct() {
        $this->tipos_proyecto = new Tipos_proyectoModel();
        $this->log = new LogModel();
        $this->empresa = new EmpresasModel();

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

            $searchValue = $request->getGet('search')['value'] ?? '';
            $orderColumn = $request->getGet('order')[0]['column'] ?? 0;
            $orderDir = $request->getGet('order')[0]['dir'] ?? 'asc';
            $start = $request->getGet('start') ?? 0;
            $length = $request->getGet('length') ?? 10;

            $columnMap = [
                0 => 'a.id',
                1 => 'a.descripcion',
                2 => 'b.nombre',
                3 => 'a.created_at'
            ];

            $builder = $this->tipos_proyecto->mdlGetTipos_proyecto($empresasID);

            // Filtro de búsqueda
            if (!empty($searchValue)) {
                $builder->groupStart()
                        ->like('a.descripcion', $searchValue)
                        ->orLike('b.nombre', $searchValue)
                        ->groupEnd();
            }

            // Conteo total antes de aplicar limit
            $totalBuilder = clone $builder;
            $recordsFiltered = $totalBuilder->countAllResults(false);

            // Ordenamiento
            if (isset($columnMap[$orderColumn])) {
                $builder->orderBy($columnMap[$orderColumn], $orderDir);
            }

            // Paginación
            $builder->limit($length, $start);

            // Ejecutar y obtener resultados
            $data = $builder->get()->getResultArray();

            // Respuesta compatible con DataTables
            return $this->response->setJSON([
                        'draw' => (int) $request->getGet('draw'),
                        'recordsTotal' => $recordsFiltered,
                        'recordsFiltered' => $recordsFiltered,
                        'data' => $data
            ]);
        }
        $titulos["title"] = lang('tipos_proyecto.title');
        $titulos["subtitle"] = lang('tipos_proyecto.subtitle');
        return view('julio101290\boilerplateprojects\Views\tipos_proyecto', $titulos);
    }

    /**
     * Read Tipos_proyecto
     */
    public function getTipos_proyecto() {

        helper('auth');

        $idUser = user()->id;
        $titulos["empresas"] = $this->empresa->mdlEmpresasPorUsuario($idUser);

        if (count($titulos["empresas"]) == "0") {

            $empresasID[0] = "0";
        } else {

            $empresasID = array_column($titulos["empresas"], "id");
        }


        $idTipos_proyecto = $this->request->getPost("idTipos_proyecto");
        $datosTipos_proyecto = $this->tipos_proyecto->whereIn('idEmpresa', $empresasID)
                        ->where("id", $idTipos_proyecto)->first();

        echo json_encode($datosTipos_proyecto);
    }

    /**
     * Save or update Tipos_proyecto
     */
    public function save() {
        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $datos = $this->request->getPost();
        if ($datos["idTipos_proyecto"] == 0) {
            try {
                if ($this->tipos_proyecto->save($datos) === false) {
                    $errores = $this->tipos_proyecto->errors();
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
            if ($this->tipos_proyecto->update($datos["idTipos_proyecto"], $datos) == false) {
                $errores = $this->tipos_proyecto->errors();
                foreach ($errores as $field => $error) {
                    echo $error . " ";
                }
                return;
            } else {
                $dateLog["description"] = lang("tipos_proyecto.logUpdated") . json_encode($datos);
                $dateLog["user"] = $userName;
                $this->log->save($dateLog);
                echo "Actualizado Correctamente";
                return;
            }
        }
        return;
    }

    /**
     * Delete Tipos_proyecto
     * @param type $id
     * @return type
     */
    public function delete($id) {
        $infoTipos_proyecto = $this->tipos_proyecto->find($id);
        helper('auth');
        $userName = user()->username;
        if (!$found = $this->tipos_proyecto->delete($id)) {
            return $this->failNotFound(lang('tipos_proyecto.msg.msg_get_fail'));
        }
        $this->tipos_proyecto->purgeDeleted();
        $logData["description"] = lang("tipos_proyecto.logDeleted") . json_encode($infoTipos_proyecto);
        $logData["user"] = $userName;
        $this->log->save($logData);
        return $this->respondDeleted($found, lang('tipos_proyecto.msg_delete'));
    }

    /**
     * Get Tipos Proyecto via AJax
     */
    public function getTiposProyectoAjax() {

        $request = service('request');
        $postData = $request->getPost();

        $response = array();

        // Read new token and assign in $response['token']
        $response['token'] = csrf_hash();

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $idEmpresa = $postData['idEmpresa'];
        $tiposProyecto = new Tipos_proyectoModel();

        if (!isset($postData['searchTerm'])) {
            // Fetch record

            $listTiposProyectos = $tiposProyecto->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("idEmpresa", $idEmpresa)
                    ->orderBy('id')
                    ->orderBy('idEmpresa')
                    ->orderBy('descripcion')
                    ->findAll();
        } else {
            $searchTerm = $postData['searchTerm'];

            $listTiposProyectos = $tiposProyecto->select('id,descripcion')
                    ->where("deleted_at", null)
                    ->where("idEmpresa", $postData["idEmpresa"])
                    ->orLike('id', $searchTerm)
                    ->orLike('descripcion', $searchTerm)
                    ->findAll();
        }

        $data = array();

        foreach ($listTiposProyectos as $tipoProyecto) {
            $data[] = array(
                "id" => $tipoProyecto['id'],
                "text" => $tipoProyecto['id'] . ' ' . $tipoProyecto['descripcion'],
            );
        }

        $response['data'] = $data;

        return $this->response->setJSON($response);
    }
}
