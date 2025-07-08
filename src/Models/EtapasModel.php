<?php

namespace App\Models;

use CodeIgniter\Model;

class EtapasModel extends Model {

    protected $table = 'etapas';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id'
        , 'idEmpresa'
        , 'descripcion'
        , 'tipoProyecto'
        , 'orden'
        , 'created_at'
        , 'updated_at'
        , 'deleted_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'idEmpresa' => 'required|greater_than[0]',
        'descripcion' => 'required|min_length[5]',
        'tipoProyecto' => 'required|greater_than[0]',
        'orden' => 'required|greater_than[0]',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function mdlGetEtapas($idEmpresas) {

        $result = $this->db->table('etapas a, empresas b')
                ->select('a.id'
                        . ',a.idEmpresa'
                        . ',a.descripcion'
                        . ',a.tipoProyecto'
                        . ',(select descripcion from tipos_proyecto tp where tp.id = a.tipoProyecto) as tipoProyectoNombre'
                        . ',a.orden'
                        . ',a.created_at'
                        . ',a.updated_at'
                        . ',a.deleted_at '
                        . ',b.nombre as nombreEmpresa')
                ->where('a.idEmpresa', 'b.id', FALSE)
                ->whereIn('a.idEmpresa', $idEmpresas);

        return $result;
    }
}
