<?php

namespace julio101290\boilerplateprojects\Models;

use CodeIgniter\Model;

class ConceptosModel extends Model {

    protected $table = 'conceptos';
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

    public function mdlGetConceptos(array $idEmpresas, array $params = []) {
        $builder = $this->db->table('conceptos a')
                ->select("
            a.id,
            a.idEmpresa,
            a.descripcion,
            a.tipoProyecto,
            (SELECT descripcion FROM tipos_proyecto tp WHERE tp.id = a.tipoProyecto) AS tipoProyectoNombre,
            a.orden,
            a.created_at,
            a.updated_at,
            a.deleted_at,
            b.nombre AS nombreEmpresa
        ")
                ->join('empresas b', 'a.idEmpresa = b.id')
                ->whereIn('a.idEmpresa', $idEmpresas);

        // Total sin filtros
        $total = $builder->countAllResults(false);

        // Filtro de búsqueda
        if (!empty($params['search'])) {
            $builder->groupStart()
                    ->like('a.descripcion', $params['search'])
                    ->orLike('b.nombre', $params['search'])
                    ->groupEnd();
        }

        // Total filtrado
        $filtered = $builder->countAllResults(false);

        // Ordenamiento
        if (!empty($params['orderBy']) && !empty($params['orderDir'])) {
            $builder->orderBy($params['orderBy'], $params['orderDir']);
        }

        // Paginación
        if (isset($params['start'], $params['length'])) {
            $builder->limit($params['length'], $params['start']);
        }

        $data = $builder->get()->getResultArray();

        return [
            'total' => $total,
            'filtered' => $filtered,
            'data' => $data,
        ];
    }
}
