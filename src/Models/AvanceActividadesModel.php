<?php

namespace App\Models;

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

        $result = $this->db->table('actividades a
                                    , empresas b
                                    , proyectos c
                                    , etapas d
                                    , conceptos e
                                    , unidades_medida f
                                    ')
                ->select('a.id,
                         a.idEmpresa
                         ,a.idProyecto
                         ,a.etapa
                         ,a.concepto
                         ,a.descripcion
                         ,a.fechaInicio
                         ,a.fechaFinal
                         ,a.cantEstimada
                         ,a.cantReal
                         ,a.unidadMedida
                         ,c.descripcion as nombreProyecto
                         ,d.descripcion as nombreEtapa
                         ,d.descripcion as nombreConcepto
                         ,case when a.status = \'01\' then \'Pendiente\'
                               when a.status = \'02\' then \'Terminado\'
                               when a.status = \'03\' then \'Cancelado\'
                               end as descripcionStatus
                         ,a.status      
                         ,a.created_at
                         ,a.updated_at
                         ,a.deleted_at 
                         ,b.nombre as nombreEmpresa
                         ,f.descripcion as nombreUnidadMedida
                        ')
                ->where('a.idEmpresa', 'b.id', FALSE)
                ->where('a.idProyecto', 'c.id', FALSE)
                ->where('a.etapa', 'd.id', FALSE)
                ->where('a.concepto', 'e.id', FALSE)
                ->where('a.unidadMedida', 'f.id', FALSE)
                ->whereIn('a.idEmpresa', $idEmpresas);

        return $result;
    }
}
