<?php

namespace julio101290\boilerplateprojects\Models;

use CodeIgniter\Model;

class ProyectosModel extends Model {

    protected $table = 'proyectos';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id', 'idEmpresa', 'idSucursal', 'tipoProyecto', 'descripcion', 'fechaInicio', 'fechaFinal', 'idCliente', 'responsable', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'idEmpresa' => 'required|greater_than[0]',
        'idSucursal' => 'required|greater_than[0]',
        'tipoProyecto' => 'required|greater_than[0]',
        'descripcion' => 'required|min_length[5]',
        'idCliente' => 'required|greater_than[0]',
        'responsable' => 'required|greater_than[0]',
        'fechaInicio' => 'required',
        'fechaFinal' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function mdlGetProyectos($idEmpresas, $params = []) {
        $builder = $this->db->table('proyectos a')
                ->join('empresas b', 'a.idEmpresa = b.id')
                ->join('branchoffices c', 'a.idSucursal = c.id')
                ->join('tipos_proyecto d', 'a.tipoProyecto = d.id')
                ->join('custumers e', 'a.idCliente = e.id')
                ->join('users f', 'a.responsable = f.id')
                ->whereIn('a.idEmpresa', $idEmpresas);

        // Detectar motor de BD y usar la sintaxis correcta para CONCAT
        if ($this->db->getPlatform() === 'MySQLi') {
            $builder->select("
            a.id,
            a.idEmpresa,
            a.idSucursal,
            a.tipoProyecto,
            a.descripcion,
            a.fechaInicio,
            a.fechaFinal,
            a.idCliente,
            CONCAT(f.firstname, ' ', f.lastname) AS responsable,
            a.created_at,
            a.updated_at,
            a.deleted_at,
            b.nombre AS nombreEmpresa,
            c.name AS nombreSucursal,
            d.descripcion AS nombreTipoDescripcion,
            CONCAT(e.firstname, ' ', e.lastname) AS nombreCliente
        ");
        } else {
            $builder->select("
            a.id,
            a.idEmpresa,
            a.idSucursal,
            a.tipoProyecto,
            a.descripcion,
            a.fechaInicio,
            a.fechaFinal,
            a.idCliente,
            (f.firstname || ' ' || f.lastname) AS responsable,
            a.created_at,
            a.updated_at,
            a.deleted_at,
            b.nombre AS nombreEmpresa,
            c.name AS nombreSucursal,
            d.descripcion AS nombreTipoDescripcion,
            (e.firstname || ' ' || e.lastname) AS \"nombreCliente\"
        ");
        }

        // Total sin filtros
        $total = $builder->countAllResults(false);

        // Filtro de búsqueda
        if (!empty($params['search'])) {
            $builder->groupStart()
                    ->like('a.descripcion', $params['search'])
                    ->orLike('b.nombre', $params['search'])
                    ->orLike('c.name', $params['search'])
                    ->orLike('d.descripcion', $params['search'])
                    ->orLike('e.firstname', $params['search'])
                    ->orLike('f.firstname', $params['search'])
                    ->groupEnd();
        }

        // Total filtrado
        $filtered = $builder->countAllResults(false);

        // Orden
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

    public function mdlGetProyecto($idEmpresas, $idProyecto) {
        $builder = $this->db->table('proyectos a');

        if ($this->db->getPlatform() === 'MySQLi') {
            // Versión para MariaDB/MySQL
            $builder->select("
            a.id,
            a.idEmpresa,
            a.idSucursal,
            a.tipoProyecto,
            a.descripcion,
            a.fechaInicio,
            a.fechaFinal,
            a.idCliente,
            a.responsable,
            a.created_at,
            a.updated_at,
            a.deleted_at,
            b.nombre AS nombreEmpresa,
            c.name AS nombreSucursal,
            d.descripcion AS nombreTipoDescripcion,
            CONCAT_WS(' ', e.firstname, e.lastname) AS nombreCliente
        ");
        } else {
            // Versión para PostgreSQL con comillas en los alias
            $builder->select("
            a.id,
            a.idEmpresa,
            a.idSucursal,
            a.tipoProyecto,
            a.descripcion,
            a.fechaInicio,
            a.fechaFinal,
            a.idCliente,
            a.responsable,
            a.created_at,
            a.updated_at,
            a.deleted_at,
            b.nombre AS \"nombreEmpresa\",
            c.name AS \"nombreSucursal\",
            d.descripcion AS \"nombreTipoDescripcion\",
            (e.firstname || ' ' || e.lastname) AS \"nombreCliente\"
        ");
        }

        $builder->join('empresas b', 'a.idEmpresa = b.id')
                ->join('branchoffices c', 'a.idSucursal = c.id')
                ->join('tipos_proyecto d', 'a.tipoProyecto = d.id')
                ->join('custumers e', 'a.idCliente = e.id')
                ->whereIn('a.idEmpresa', $idEmpresas)
                ->where('a.id', $idProyecto);

        $result = $builder->get()->getResultArray();

        return $result[0] ?? null;
    }

    public function mdlResumenProyecto($idProyecto) {
        $builder = $this->db->table('proyectos a');

        if ($this->db->getPlatform() === 'MySQLi') {
            // MariaDB/MySQL
            $builder->select('
            a.descripcion AS descripcionProyecto,
            c.descripcion AS descripcionEtapa,
            SUM(COALESCE(b.costoTotalEstimado, 0)) AS costoTotalEstimado
        ');
        } else {
            // PostgreSQL
            $builder->select('
            a.descripcion AS "descripcionProyecto",
            c.descripcion AS "descripcionEtapa",
            SUM(COALESCE(b.costoTotalEstimado, 0)) AS "costoTotalEstimado"
        ');
        }

        $builder->join('actividades b', 'a.id = b.idProyecto')
                ->join('etapas c', 'b.etapa = c.id')
                ->join('conceptos d', 'b.concepto = d.id')
                ->join('empresas e', 'a.idEmpresa = e.id')
                ->where('a.id', $idProyecto)
                ->groupBy(['a.descripcion', 'c.descripcion']);

        $result = $builder->get()->getResultArray();
        return $result;
    }

    public function mdlPresupuestoProyecto($idProyecto) {
        $builder = $this->db->table('proyectos a');

        if ($this->db->getPlatform() === 'MySQLi') {
            // MariaDB / MySQL
            $builder->select('
            a.descripcion as descripcionProyecto,
            c.descripcion as descripcionEtapa,
            d.descripcion as descripcionConcepto,
            b.descripcion as descripcionActividad,
            b.cantEstimada,
            COALESCE(b.costoUnitario, 0.00) as costoUnitario,
            f.descripcion as descripcionUnidadMedida,
            COALESCE(b.costoTotalEstimado, 0.00) as costoTotalEstimado
        ');
        } else {
            // PostgreSQL
            $builder->select('
            a.descripcion as "descripcionProyecto",
            c.descripcion as "descripcionEtapa",
            d.descripcion as "descripcionConcepto",
            b.descripcion as "descripcionActividad",
            b.cantEstimada,
            COALESCE(b.costoUnitario, 0.00) as "costoUnitario",
            f.descripcion as "descripcionUnidadMedida",
            COALESCE(b.costoTotalEstimado, 0.00) as "costoTotalEstimado"
        ');
        }

        $builder->join('actividades b', 'a.id = b.idProyecto');
        $builder->join('etapas c', 'b.etapa = c.id');
        $builder->join('conceptos d', 'b.concepto = d.id');
        $builder->join('unidades_medida f', 'b.unidadMedida = f.id');
        $builder->join('empresas e', 'a.idEmpresa = e.id');
        $builder->where('a.id', $idProyecto);
        $builder->orderBy('c.orden', 'asc');
        $builder->orderBy('d.orden', 'asc');

        $result = $builder->get()->getResultArray();
        return $result;
    }
}
