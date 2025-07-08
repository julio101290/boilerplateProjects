<?php

namespace App\Models;

use CodeIgniter\Model;

class ActividadesModel extends Model {

    protected $table = 'actividades';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id'
        , 'idEmpresa'
        , 'idProyecto'
        , 'etapa'
        , 'concepto'
        , 'descripcion'
        , 'fechaInicio'
        , 'fechaFinal'
        , 'cantEstimada'
        , 'cantReal'
        , 'unidadMedida'
        , 'status'
        , 'porcAvanzado'
        , 'idUsuario'
        , 'idProveedor'
        , 'modalidadActividad'
        , 'costoUnitario'
        , 'costoTotalEstimado'
        , 'costoTotalReal'
        , 'producto'
        , 'created_at'
        , 'updated_at'
        , 'deleted_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
        'idEmpresa' => 'required|greater_than[0]',
        'idProyecto' => 'required|greater_than[0]',
        'etapa' => 'required|greater_than[0]',
        'concepto' => 'required|greater_than[0]',
        'descripcion' => 'required|min_length[5]',
        'cantEstimada' => 'required|greater_than[0]',
        'fechaInicio' => 'required',
        'fechaFinal' => 'required',
        'unidadMedida' => 'required|greater_than[0]',
        'status' => 'required|in_list[01,02,03]',
        'idUsuario' => 'required',
        'idProveedor' => 'required',
        'modalidadActividad' => 'required|in_list[01,02]',
        'costoUnitario' => 'required',
        'costoTotalEstimado' => 'required',
        'costoTotalReal' => 'required',
        'producto' => 'required',
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
                         ,e.descripcion as nombreConcepto
                         ,case when a.status = \'01\' then \'Pendiente\'
                               when a.status = \'02\' then \'Terminado\'
                               when a.status = \'03\' then \'Cancelado\'
                               end as descripcionStatus
                         ,a.status    
                         ,a.costoUnitario
                         ,a.costoTotalEstimado
                         ,a.costoTotalReal
                         ,a.producto
                         ,a.created_at
                         ,a.updated_at
                         ,a.deleted_at 
                         ,b.nombre as nombreEmpresa
                         ,f.descripcion as nombreUnidadMedida
                         ,ifnull(a.porcAvanzado,0) as porcAvanzado
                         ,(select concat(g.username,\' - \',g.firstname,\' \',lastname) from users g where g.id = a.idUsuario ) as  username
                         ,(select concat(g.firstname,\' \',g.lastname) from proveedores g where g.id = a.idProveedor ) as  nombreProveedor
                         , case when a.modalidadActividad = \'01\' then \'Interna\'
                              when a.modalidadActividad = \'02\' then \'SubContratada\'
                              when a.modalidadActividad = \'03\' then \'Insumo\'
                         end as modalidadActividad     
                                

                        ')
                ->where('a.idEmpresa', 'b.id', FALSE)
                ->where('a.idProyecto', 'c.id', FALSE)
                ->where('a.etapa', 'd.id', FALSE)
                ->where('a.concepto', 'e.id', FALSE)
                ->where('a.unidadMedida', 'f.id', FALSE)
                ->whereIn('a.idEmpresa', $idEmpresas);

        return $result;
    }

    /**
     * Search by filters
     */
    public function mdlGetActividadesFilters($empresas
            , $from
            , $to
            , $pendientes
            , $empresa = 0
            , $proyecto = 0
            , $responsable = 0
    ) {



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
                         ,e.descripcion as nombreConcepto
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
                         ,ifnull(a.porcAvanzado,0) as porcAvanzado
                         ,(select concat(g.username,\' - \',g.firstname,\' \',lastname) from users g where g.id = a.idUsuario ) as  username
                         ,(select concat(g.firstname,\' \',g.lastname) from proveedores g where g.id = a.idProveedor ) as  nombreProveedor
                         , case when a.modalidadActividad = \'01\' then \'Interna\'
                              when a.modalidadActividad = \'02\' then \'SubContratada\'
                              when a.modalidadActividad = \'03\' then \'Insumo\'
                         end as modalidadActividad     
                        
                         ,a.costoUnitario
                         ,a.costoTotalEstimado
                         ,a.costoTotalReal
                         ,a.producto

                        ')
                ->where('a.idEmpresa', 'b.id', FALSE)
                ->where('a.idProyecto', 'c.id', FALSE)
                ->where('a.etapa', 'd.id', FALSE)
                ->where('a.concepto', 'e.id', FALSE)
                ->where('a.unidadMedida', 'f.id', FALSE)
                ->where('a.fechaInicio >=', $from . ' 00:00:00')
                ->where('a.fechaInicio <=', $to . ' 23:59:59')
                ->groupStart()
                ->where('\'false\'', $pendientes, true)
                ->orWhere('ifnull(a.porcAvanzado,0)<', 100, true)
                ->groupEnd()
                ->groupStart()
                ->where('\'0\'', $empresa, true)
                ->orWhere('a.idEmpresa', $empresa)
                ->groupEnd()
                ->groupStart()
                ->where('\'0\'', $proyecto, true)
                ->orWhere('a.idProyecto', $proyecto)
                ->groupEnd()
                ->groupStart()
                ->where('\'0\'', $responsable, true)
                ->orWhere('a.idUsuario', $responsable)
                ->groupEnd()
                ->whereIn('a.idEmpresa', $empresas);

        return $result;
    }

    /**
     * ACtividades para el cubo
     */
    public function mdlGetActividadesFiltersCubo($empresas
            , $from
            , $to
            , $pendientes
            , $empresa = 0
            , $proyecto = 0
            , $responsable = 0
    ) {



        $result = $this->db->table('actividades a
                                    , empresas b
                                    , proyectos c
                                    , etapas d
                                    , conceptos e
                                    , unidades_medida f
                                    ')
                        ->select('
                         b.nombre as nombreEmpresa
                         ,c.descripcion as nombreProyecto
                         ,d.descripcion as nombreEtapa
                         ,e.descripcion as nombreConcepto
                          ,a.descripcion as descripcionActividad
                         ,a.cantEstimada
                         ,a.cantReal
                         ,ifnull(a.porcAvanzado,0) as porcAvanzado
                         ,case when a.status = \'01\' then \'Pendiente\'
                               when a.status = \'02\' then \'Terminado\'
                               when a.status = \'03\' then \'Cancelado\'
                               end as descripcionStatus
                         ,f.descripcion as nombreUnidadMedida
                         ,(select concat(g.username,\' - \',g.firstname,\' \',lastname) from users g where g.id = a.idUsuario ) as  username
                         ,(select concat(g.firstname,\' \',g.lastname) from proveedores g where g.id = a.idProveedor ) as  nombreProveedor
                         , case when a.modalidadActividad = \'01\' then \'Interna\'
                              when a.modalidadActividad = \'02\' then \'SubContratada\'
                              when a.modalidadActividad = \'03\' then \'Insumo\'
                         end as modalidadActividad     
                                
                         ,a.costoUnitario
                         ,a.costoTotalEstimado
                         ,a.costoTotalReal
                         ,a.producto
                         
                        ')
                        ->where('a.idEmpresa', 'b.id', FALSE)
                        ->where('a.idProyecto', 'c.id', FALSE)
                        ->where('a.etapa', 'd.id', FALSE)
                        ->where('a.concepto', 'e.id', FALSE)
                        ->where('a.unidadMedida', 'f.id', FALSE)
                        ->where('a.fechaInicio >=', $from . ' 00:00:00')
                        ->where('a.fechaInicio <=', $to . ' 23:59:59')
                        ->groupStart()
                        ->where('\'false\'', $pendientes, true)
                        ->orWhere('ifnull(a.porcAvanzado,0)<', 100, true)
                        ->groupEnd()
                        ->groupStart()
                        ->where('\'0\'', $empresa, true)
                        ->orWhere('a.idEmpresa', $empresa)
                        ->groupEnd()
                        ->groupStart()
                        ->where('\'0\'', $proyecto, true)
                        ->orWhere('a.idProyecto', $proyecto)
                        ->groupEnd()
                        ->groupStart()
                        ->where('\'0\'', $responsable, true)
                        ->orWhere('a.idUsuario', $responsable)
                        ->groupEnd()
                        ->whereIn('a.idEmpresa', $empresas)->get()->getResultArray();

        return $result;
    }

    public function mdlTotalRegistros($desdeFecha, $hastaFecha) {

        $result = $this->db->table('actividades a')
                ->selectCount('id', 'totalRegistros')
                ->where('created_at >=', $desdeFecha)
                ->where('created_at <=',$hastaFecha)
                ->get()->getResultArray();
        
        return $result;
    }
}
