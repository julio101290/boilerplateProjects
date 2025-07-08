<?php

namespace App\Models;

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

    public function mdlGetProyectos($idEmpresas) {

        $result = $this->db->table('proyectos a
                                    , empresas b
                                    , branchoffices c
                                    , tipos_proyecto d
                                    , custumers e
                                    , users f
'
                )
                ->select('a.id
                        ,a.idEmpresa
                        ,a.idSucursal
                        ,a.tipoProyecto
                        ,a.descripcion
                        ,a.fechaInicio
                        ,a.fechaFinal
                        ,a.idCliente
                        ,concat(f.firstname,f.lastname) as responsable
                        ,a.created_at
                        ,a.updated_at
                        ,a.deleted_at 
                        ,b.nombre as nombreEmpresa
                        ,c.name as nombreSucursal
                        ,d.descripcion as nombreTipoDescripcion
                        ,concat(e.firstname,e.lastname) as nombreCliente
                        
                        ')
                ->where('a.idEmpresa', 'b.id', FALSE)
                ->where('a.idSucursal', 'c.id', FALSE)
                ->where('a.tipoProyecto', 'd.id', FALSE)
                ->where('a.idCliente', 'e.id', FALSE)
                ->where('a.responsable', 'f.id', FALSE)
                ->whereIn('a.idEmpresa', $idEmpresas);

        return $result;
    }

    public function mdlGetProyecto($idEmpresas, $idProyecto) {

        $result = $this->db->table('proyectos a
                                    , empresas b
                                    , branchoffices c
                                    , tipos_proyecto d
                                    , custumers e
'
                        )
                        ->select('a.id
                        ,a.idEmpresa
                        ,a.idSucursal
                        ,a.tipoProyecto
                        ,a.descripcion
                        ,a.fechaInicio
                        ,a.fechaFinal
                        ,a.idCliente
                        ,a.responsable
                        ,a.created_at
                        ,a.updated_at
                        ,a.deleted_at 
                        ,b.nombre as nombreEmpresa
                        ,c.name as nombreSucursal
                        ,d.descripcion as nombreTipoDescripcion
                        ,concat(e.firstname,e.lastname) as nombreCliente
                        
                        ')
                        ->where('a.idEmpresa', 'b.id', FALSE)
                        ->where('a.idSucursal', 'c.id', FALSE)
                        ->where('a.tipoProyecto', 'd.id', FALSE)
                        ->where('a.idCliente', 'e.id', FALSE)
                        ->whereIn('a.idEmpresa', $idEmpresas)
                        ->where('a.id', $idProyecto, FALSE)->get()->getResultArray();

        return $result[0];
    }

    public function mdlResumenProyecto($idProyecto) {

        $result = $this->db->table('proyectos a, actividades b, etapas c, conceptos d, empresas e')
                        ->select('a.descripcion as descripcionProyecto,c.descripcion as descripcionEtapa,sum(ifnull(b.costoTotalEstimado,0)) as costoTotalEstimado')
                        ->where('a.id', $idProyecto)
                        ->where('a.idEmpresa', 'e.id', FALSE)
                        ->where('b.etapa', 'c.id', FALSE)
                        ->where('b.concepto', 'd.id', FALSE)
                        ->where('a.id', 'b.idProyecto', FALSE)
                        ->groupBy(array('a.descripcion', 'c.descripcion'))
                        ->get()->getResultArray();

        return $result;
    }

    public function mdlPresupuestoProyecto($idProyecto) {

        $result = $this->db->table('proyectos a
                                    , actividades b
                                    , etapas c
                                    , conceptos d
                                    , unidades_medida f
                                    , empresas e')
                        ->select('a.descripcion as descripcionProyecto
                             ,c.descripcion as descripcionEtapa
                             ,d.descripcion as descripcionConcepto
                             ,b.descripcion as descripcionActividad
                             ,b.cantEstimada 
                             ,ifnull(b.costoUnitario ,0.00) as costoUnitario
                             ,f.descripcion as descripcionUnidadMedida 
                             ,ifnull(b.costoTotalEstimado,0.00) as costoTotalEstimado')
                        ->where('a.id', $idProyecto)
                        ->where('a.idEmpresa', 'e.id', FALSE)
                        ->where('b.etapa', 'c.id', FALSE)
                        ->where('b.concepto', 'd.id', FALSE)
                        ->where('b.unidadMedida', 'f.id', FALSE)
                        ->where('a.id', 'b.idProyecto', FALSE)
                        ->orderBy('c.orden asc')
                        ->orderBy('d.orden asc')
                        ->get()->getResultArray();

        return $result;
    }
}
