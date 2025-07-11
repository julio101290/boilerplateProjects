<?php

namespace julio101290\boilerplateprojects\Models;

use CodeIgniter\Model;

class AvanceActividadesModel extends Model {

    protected $table = 'avance_actividad';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id'
        , 'idEmpresa'
        , 'idActividad'
        , 'fecha'
        , 'descripcion'
        , 'porcentaje'
        , 'horas'
        , 'created_at'
        , 'updated_at'
        , 'deleted_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'idActividad' => 'required|greater_than[0]',
        'descripcion' => 'required|min_length[5]',
        'horas' => 'required|greater_than[0]',
        'porcentaje' => 'required|greater_than[0]',
        'fecha' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function mdlGetActividades($idEmpresas) {
        $result = $this->db->table('actividades a')
                ->select(
                        'a.id,
             a.idEmpresa,
             a.idProyecto,
             a.etapa,
             a.concepto,
             a.descripcion,
             a.fechaInicio,
             a.fechaFinal,
             a.cantEstimada,
             a.cantReal,
             a.unidadMedida,
             c.descripcion AS nombreProyecto,
             d.descripcion AS nombreEtapa,
             e.descripcion AS nombreConcepto,
             CASE 
                 WHEN a.status = \'01\' THEN \'Pendiente\'
                 WHEN a.status = \'02\' THEN \'Terminado\'
                 WHEN a.status = \'03\' THEN \'Cancelado\'
             END AS descripcionStatus,
             a.status,
             a.created_at,
             a.updated_at,
             a.deleted_at,
             b.nombre AS nombreEmpresa,
             f.descripcion AS nombreUnidadMedida'
                )
                ->join('empresas b', 'a.idEmpresa = b.id')
                ->join('proyectos c', 'a.idProyecto = c.id')
                ->join('etapas d', 'a.etapa = d.id')
                ->join('conceptos e', 'a.concepto = e.id')
                ->join('unidades_medida f', 'a.unidadMedida = f.id')
                ->whereIn('a.idEmpresa', $idEmpresas);

        return $result;
    }
}
