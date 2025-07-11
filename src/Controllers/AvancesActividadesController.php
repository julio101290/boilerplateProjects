<?php

namespace julio101290\boilerplateprojects\Controllers;

use App\Controllers\BaseController;
use julio101290\boilerplateprojects\Models\AvanceActividadesModel;
use julio101290\boilerplateprojects\Models\ActividadesModel;
use julio101290\boilerplatelog\Models\LogModel;
use CodeIgniter\API\ResponseTrait;
use Exception;

class AvancesActividadesController extends BaseController {

    use ResponseTrait;

    protected $log;
    protected $avancesActividades;
    protected $actividades;

    public function __construct() {

        $this->avancesActividades = new AvanceActividadesModel();
        $this->log = new LogModel();
        $this->actividades = new ActividadesModel();
        helper('menu');
    }

    public function index() {
        if ($this->request->isAJAX()) {
            $request = service('request');
            $db = db_connect();
            $builder = $this->avancesActividades->getBaseQuery();

            // Parámetros de DataTables
            $search = $request->getPost('search')['value'] ?? '';
            $start = (int) $request->getPost('start') ?? 0;
            $length = (int) $request->getPost('length') ?? 10;
            $orderCol = $request->getPost('order')[0]['column'] ?? 0;
            $orderDir = $request->getPost('order')[0]['dir'] ?? 'asc';
            $columns = $request->getPost('columns');

            // Columnas disponibles (orden debe coincidir con DataTable)
            $fields = ['id', 'idActividad', 'fechaActual', 'descripcion', 'porcentaje', 'horas', 'created_at', 'updated_at', 'deleted_at'];
            $orderBy = $fields[$orderCol] ?? 'id';

            // Filtro de búsqueda
            if (!empty($search)) {
                $builder->groupStart();
                foreach ($fields as $field) {
                    $builder->orLike($field, $search);
                }
                $builder->groupEnd();
            }

            // Total sin filtro
            $totalRecords = $this->avancesActividades
                    ->where('deleted_at', null)
                    ->countAllResults(false); // false evita reset del query builder
            // Total con filtro
            $filteredRecords = clone $builder;
            $totalFiltered = $filteredRecords->countAllResults();

            // Orden y paginación
            $builder->orderBy($orderBy, $orderDir)
                    ->limit($length, $start);

            // Ejecutar
            $query = $builder->get();
            $data = $query->getResult();

            // Formato final
            return $this->response->setJSON([
                        'draw' => (int) $request->getPost('draw'),
                        'recordsTotal' => $totalRecords,
                        'recordsFiltered' => $totalFiltered,
                        'data' => $data
            ]);
        }
        $titulos["title"] = "Avance de actividad";
        $titulos["subtitle"] = "Seguimiento de actividades";
        return view('julio101290\boilerplateprojects\Views\avanceActividades', $titulos);
    }

    /**
     * Read Payments
     */
    public function getAvanceActividad() {
        $idAvance = $this->request->getPost("idAvance");
        $avanceActividad = $this->avancesActividades->find($idAvance);
        echo json_encode($avanceActividad);
    }

    /**
     * Guardar o actualizar avance de actividad
     */
    public function save() {
        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $datos = $this->request->getPost();

        $auth = service('authentication');

        if (!$auth->check()) {

            echo "No ha iniciado Session";
            return;
        }

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;

        $this->avancesActividades->db->transBegin();

        $datosActividad = $this->actividades->select("*")->where("id", $datos["idActividad"])->asArray()->first();

        if ($datosActividad["porcAvanzado"] == 'null'
                or $datosActividad["porcAvanzado"] == 'NULL'
                or $datosActividad["porcAvanzado"] == ''
        ) {

            $datosActividad["porcAvanzado"] = 0;
        }

        if ($datos["porcentaje"] == 'null'
                or $datos["porcentaje"] == 'NULL'
                or $datos["porcentaje"] == ''
        ) {

            $datos["porcentaje"] = 0;
        }



        if (($datosActividad["porcAvanzado"] + $datos["porcentaje"]) > 100) {

            echo " La cantidad de avance supera el 100%, favor de verificar ";
            return;
        }

        try {



            if ($this->avancesActividades->save($datos) === false) {

                $errores = $this->avancesActividades->errors();
                foreach ($errores as $field => $error) {
                    echo $error . " \n ";
                }

                $this->avancesActividades->db->transRollback();
                return;
            }
            $dateLog["description"] = " Se agrego el avance de actividad" . json_encode($datos);
            $dateLog["user"] = $userName;
            $this->log->save($dateLog);

            try {

                $datosActividadGuardar["porcAvanzado"] = $datosActividad["porcAvanzado"] + $datos["porcentaje"];
                $datosActividadGuardar["cantReal"] = $datosActividad["cantReal"] + $datos["horas"];

                $datosActividadGuardar["costoTotalReal"] = $datosActividadGuardar["cantReal"] * $datosActividad["costoUnitario"];

                if ($datosActividadGuardar["porcAvanzado"] == 100) {

                    $datosActividadGuardar["status"] = "02";
                }

                $this->actividades->update($datos["idActividad"], $datosActividadGuardar);

                $this->avancesActividades->transCommit();
            } catch (Exception $e) {

                echo $e->getMessage();
                $this->avancesActividades->db->transRollback();
            }

            echo "Guardado Correctamente";
        } catch (\PHPUnit\Framework\Exception $ex) {

            echo "Error al guardar " . $ex->getMessage();
            $this->avancesActividades->db->transRollback();
        }

        return;
    }

    public function ctrGetAvances($id) {

        helper('auth');
        $userName = user()->username;
        $idUser = user()->id;
        $datos = $this->request->getPost();

        $auth = service('authentication');
        if (!$auth->check()) {

            echo "No ha iniciado Session";
            return;
        }




        $request = service('request');
        $builder = $this->avancesActividades
                ->select('id, idActividad, fecha, descripcion, porcentaje, horas, created_at, updated_at, deleted_at')
                ->where('deleted_at', null)
                ->where('idActividad', $id);

        // Parámetros de DataTables
        $search = $request->getPost('search')['value'] ?? '';
        $start = (int) $request->getPost('start') ?? 0;
        $length = (int) $request->getPost('length') ?? 10;
        $orderCol = $request->getPost('order')[0]['column'] ?? 0;
        $orderDir = $request->getPost('order')[0]['dir'] ?? 'asc';
        $columns = $request->getPost('columns');

        // Columnas disponibles
        $fields = ['id', 'idActividad', 'fecha', 'descripcion', 'porcentaje', 'horas', 'created_at', 'updated_at', 'deleted_at'];
        $orderBy = $fields[$orderCol] ?? 'id';

        // Filtro de búsqueda
        if (!empty($search)) {
            $builder->groupStart();
            foreach ($fields as $field) {
                $builder->orLike($field, $search);
            }
            $builder->groupEnd();
        }

        // Total sin filtro
        $totalRecords = $this->avancesActividades
                ->where('deleted_at', null)
                ->where('idActividad', $id)
                ->countAllResults(false); // false: para no reiniciar el builder
        // Total con filtro
        $filteredBuilder = clone $builder;
        $totalFiltered = $filteredBuilder->countAllResults();

        // Aplicar orden y paginación
        $builder->orderBy($orderBy, $orderDir)
                ->limit($length, $start);

        // Obtener resultados
        $query = $builder->get();
        $data = $query->getResult();

        // Formato JSON para DataTables
        return $this->response->setJSON([
                    'draw' => (int) $request->getPost('draw'),
                    'recordsTotal' => $totalRecords,
                    'recordsFiltered' => $totalFiltered,
                    'data' => $data,
        ]);
    }

    /**
     * Delete Payments
     * @param type $id
     * @return type
     */
    public function delete($id) {

        $infoAvance = $this->avancesActividades->find($id);

        $infoActividad = $this->actividades->find($infoAvance["idActividad"]);

        $nuevosDatosActividad["cantReal"] = $infoActividad["cantReal"] - $infoAvance["horas"];

        $nuevosDatosActividad["costoTotalReal"] = $nuevosDatosActividad["cantReal"] * $infoActividad["costoUnitario"];

        $nuevosDatosActividad["porcAvanzado"] = $infoActividad["porcAvanzado"] - $infoAvance["porcentaje"];

        $nuevosDatosActividad["status"] = "01";

        helper('auth');

        $auth = service('authentication');
        if (!$auth->check()) {

            echo "no conectado";
            return redirect()->route('login');
        }
        $userName = user()->username;

        $this->avancesActividades->db->transBegin();

        if (!$found = $this->avancesActividades->delete($id)) {
            return $this->failNotFound(lang('payments.msg.msg_get_fail'));
        }

        /**
         * Actualizamos datos en actividades
         */
        try {

            $resultActividades = $this->actividades->update($infoAvance["idActividad"], $nuevosDatosActividad);
        } catch (Exception $ex) {

            $this->avancesActividades->db->transRollback();
            return;
        }


        try {
            $this->avancesActividades->purgeDeleted();
            $logData["description"] = "Se elimino avance con los siguientes datos" . json_encode($infoAvance);
            $logData["user"] = $userName;
            $this->log->save($logData);

            $this->avancesActividades->db->transCommit();

            return $this->respondDeleted($found, "Avance eliminado correctamente ");
        } catch (Exception $ex) {

            return $this->respond(false, "Error Al eliminar ");
        }
    }
}
