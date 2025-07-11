<?php

namespace julio101290\boilerplateprojects\Models;

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

    public function mdlGetActividades(array $idEmpresas) {
        $platform = $this->db->getPlatform(); // 'MySQLi' o 'Postgre'

        $joinUnidad = $platform === 'Postgre' ? 'CAST(a.unidadMedida AS INTEGER) = f.id = f.id' : 'a.unidadMedida = f.id';
        
        $q = $platform === 'Postgre' ? "\"" : "\"";
        
        // Variables SQL según motor
        $porcAvanzado = $platform === 'MySQLi' ? 'IFNULL(a.porcAvanzado, 0) AS porcAvanzado' : 'COALESCE(a."porcAvanzado", 0) AS "porcAvanzado"';

        $username = $platform === 'MySQLi' ? "(SELECT CONCAT(g.username, ' - ', g.firstname, ' ', g.lastname) FROM users g WHERE g.id = a.idUsuario) AS username" : "(SELECT g.username || ' - ' || g.firstname || ' ' || g.lastname FROM users g WHERE g.id = a.".$q."idUsuario".$q.") AS username";

        $nombreProveedor = $platform === 'MySQLi' ? "(SELECT CONCAT(g.firstname, ' ', g.lastname) FROM proveedores g WHERE g.id = a.idProveedor) AS nombreProveedor" : "(SELECT g.firstname || ' ' || g.lastname FROM proveedores g WHERE g.id = a.".$q."idProveedor".$q.") AS nombreProveedor";

        return $this->db->table('actividades a')
                        ->select("
            a.id,
            a.".$q."idEmpresa".$q.",
            a.".$q."idProyecto".$q.",
            a.etapa,
            a.concepto,
            a.descripcion,
            a.".$q."fechaInicio".$q.",
            a.".$q."fechaFinal".$q.",
            a.".$q."cantEstimada".$q.",
            a.".$q."cantReal".$q.",
            a.".$q."unidadMedida".$q.",
            c.descripcion AS ".$q."nombreProyecto".$q.",
            d.descripcion AS ".$q."nombreEtapa".$q.",
            e.descripcion AS ".$q."nombreConcepto,".$q."
            CASE
                WHEN a.status = '01' THEN 'Pendiente'
                WHEN a.status = '02' THEN 'Terminado'
                WHEN a.status = '03' THEN 'Cancelado'
            END AS ".$q."descripcionStatus".$q.",
            a.status,
            a.".$q."costoUnitario".$q.",
            a.".$q."costoTotalEstimado".$q.",
            a.".$q."costoTotalReal".$q.",
            a.producto,
            a.created_at,
            a.updated_at,
            a.deleted_at,
            b.nombre AS ".$q."nombreEmpresa".$q.",
            f.descripcion AS ".$q."nombreUnidadMedida".$q.",
            {$porcAvanzado},
            {$username},
            {$nombreProveedor},
            CASE
                WHEN a.".$q."modalidadActividad".$q." = '01' THEN 'Interna'
                WHEN a.".$q."modalidadActividad".$q." = '02' THEN 'SubContratada'
                WHEN a.".$q."modalidadActividad".$q." = '03' THEN 'Insumo'
            END AS ".$q."modalidadActividad".$q."
        ")
                        ->join('empresas b', "a.".$q."idEmpresa".$q." = b.id")
                        ->join('proyectos c', "a.".$q."idProyecto".$q." = c.id")
                        ->join('etapas d', 'a.etapa = d.id')
                        ->join('conceptos e', 'a.concepto = e.id')
                        ->join('unidades_medida f', $joinUnidad)
                        ->whereIn("a.".$q."idEmpresa".$q."", $idEmpresas);
    }

    /**
     * Search by filters
     */
    public function mdlGetActividadesFilters(
            array $empresas,
            string $from,
            string $to,
            bool $pendientes,
            int $empresa = 0,
            int $proyecto = 0,
            int $responsable = 0
    ) {
        $platform = $this->db->getPlatform();
        
        $q = $platform === 'Postgre' ? "\"" : "\"";
        $joinUnidad = $platform === 'Postgre' ? 'CAST(a."unidadMedida" AS INTEGER) = f.id' : 'a.unidadMedida = f.id';

        // Adaptar funciones según el motor
        $porcAvanzado = $platform === 'MySQLi' ? 'IFNULL(a.porcAvanzado, 0) AS porcAvanzado' : 'COALESCE(a."porcAvanzado", 0) AS "porcAvanzado"';

        $username = $platform === 'MySQLi' ? "(SELECT CONCAT(g.username, ' - ', g.firstname, ' ', g.lastname) FROM users g WHERE g.id = a.idUsuario) AS username" : "(SELECT g.username || ' - ' || g.firstname || ' ' || g.lastname FROM users g WHERE g.id = a.".$q."idUsuario".$q.") AS username";

        $nombreProveedor = $platform === 'MySQLi' ? "(SELECT CONCAT(g.firstname, ' ', g.lastname) FROM proveedores g WHERE g.id = a.idProveedor) AS nombreProveedor" : "(SELECT g.firstname || ' ' || g.lastname FROM proveedores g WHERE g.id = a.".$q."idProveedor".$q.") AS ".$q."nombreProveedor".$q."";

        $builder = $this->db->table('actividades a')
                ->select("
            a.id,
            a.".$q."idEmpresa".$q.",
            a.".$q."idProyecto".$q.",
            a.etapa,
            a.concepto,
            a.descripcion,
            a.".$q."fechaInicio".$q.",
            a.".$q."fechaFinal".$q.",
            a.".$q."cantEstimada".$q.",
            a.".$q."cantReal".$q.",
            a.".$q."unidadMedida".$q.",
            c.descripcion AS ".$q."nombreProyecto".$q.",
            d.descripcion AS ".$q."nombreEtapa".$q.",
            e.descripcion AS ".$q."nombreConcepto".$q.",
            CASE
                WHEN a.status = '01' THEN 'Pendiente'
                WHEN a.status = '02' THEN 'Terminado'
                WHEN a.status = '03' THEN 'Cancelado'
            END AS ".$q."descripcionStatus".$q.",
            a.status,
            a.created_at,
            a.updated_at,
            a.deleted_at,
            b.nombre AS ".$q."nombreEmpresa".$q.",
            f.descripcion AS ".$q."nombreUnidadMedida".$q.",
            {$porcAvanzado},
            {$username},
            {$nombreProveedor},
            CASE
                WHEN a.".$q."modalidadActividad".$q." = '01' THEN 'Interna'
                WHEN a.".$q."modalidadActividad".$q." = '02' THEN 'SubContratada'
                WHEN a.".$q."modalidadActividad".$q." = '03' THEN 'Insumo'
            END AS ".$q."modalidadActividad".$q.",
            a.".$q."costoUnitario".$q.",
            a.".$q."costoTotalEstimado".$q.",
            a.".$q."costoTotalReal".$q.",
            a.producto
        ")
                ->join('empresas b', "a.".$q."idEmpresa".$q." = b.id")
                ->join('proyectos c', 'a.idProyecto = c.id')
                ->join('etapas d', 'a.etapa = d.id')
                ->join('conceptos e', 'a.concepto = e.id')
                ->join('unidades_medida f', $joinUnidad)
                ->whereIn('a.idEmpresa', $empresas)
                ->where('a.fechaInicio >=', "{$from} 00:00:00")
                ->where('a.fechaInicio <=', "{$to} 23:59:59");

        // Filtro: mostrar sólo actividades pendientes si se solicita
        if ($pendientes) {
            $builder->groupStart()
                    ->where("a.".$q."porcAvanzado".$q." <", 100)
                    ->orWhere("a.".$q."porcAvanzado".$q." IS NULL", null, false)
                    ->groupEnd();
        }

        // Filtros opcionales
        if ($empresa != 0) {
            $builder->where("a.".$q."idEmpresa".$q."", $empresa);
        }

        if ($proyecto != 0) {
            $builder->where("a.".$q."idProyecto".$q."", $proyecto);
        }

        if ($responsable != 0) {
            $builder->where("a.".$q."idUsuario".$q."", $responsable);
        }

        return $builder;
    }

    /**
     * Actividades para el cubo
     */
    public function mdlGetActividadesFiltersCubo(
            array $empresas,
            string $from,
            string $to,
            bool $pendientes,
            int $empresa = 0,
            int $proyecto = 0,
            int $responsable = 0
    ) {
        $platform = $this->db->getPlatform();
        $joinUnidad = $platform === 'Postgre' ? 'CAST(a.unidadMedida AS INTEGER) = f.id' : 'a.unidadMedida = f.id';
        // Compatibilidad con funciones de cada motor
        $porcAvanzado = $platform === 'MySQLi' ? 'IFNULL(a.porcAvanzado, 0) AS porcAvanzado' : 'COALESCE(a.porcAvanzado, 0) AS porcAvanzado';

        $username = $platform === 'MySQLi' ? "(SELECT CONCAT(g.username, ' - ', g.firstname, ' ', g.lastname) FROM users g WHERE g.id = a.idUsuario) AS username" : "(SELECT g.username || ' - ' || g.firstname || ' ' || g.lastname FROM users g WHERE g.id = a.idUsuario) AS username";

        $nombreProveedor = $platform === 'MySQLi' ? "(SELECT CONCAT(g.firstname, ' ', g.lastname) FROM proveedores g WHERE g.id = a.idProveedor) AS nombreProveedor" : "(SELECT g.firstname || ' ' || g.lastname FROM proveedores g WHERE g.id = a.idProveedor) AS nombreProveedor";

        $builder = $this->db->table('actividades a')
                ->select("
            b.nombre AS ".$q."nombreEmpresa".$q.",
            c.descripcion AS ".$q."nombreProyecto".$q.",
            d.descripcion AS ".$q."nombreEtapa".$q.",
            e.descripcion AS ".$q."nombreConcepto".$q.",
            a.descripcion AS ".$q."descripcionActividad".$q.",
            a.".$q."cantEstimada".$q.",
            a.cantReal,
            {$porcAvanzado},
            CASE
                WHEN a.status = '01' THEN 'Pendiente'
                WHEN a.status = '02' THEN 'Terminado'
                WHEN a.status = '03' THEN 'Cancelado'
            END AS ".$q."descripcionStatus".$q.",
            f.descripcion AS ".$q."nombreUnidadMedida".$q.",
            {$username},
            {$nombreProveedor},
            CASE
                WHEN a.".$q."modalidadActividad".$q." = '01' THEN 'Interna'
                WHEN a.".$q."modalidadActividad".$q." = '02' THEN 'SubContratada'
                WHEN a.".$q."modalidadActividad".$q." = '03' THEN 'Insumo'
            END AS ".$q."modalidadActividad".$q.",
            a.".$q."costoUnitario".$q.",
            a.".$q."costoTotalEstimado".$q.",
            a.".$q."costoTotalReal".$q.",
            a.producto
        ")
                ->join('empresas b', 'a.".$q."idEmpresa".$q." = b.id')
                ->join('proyectos c', 'a.".$q."idProyecto".$q." = c.id')
                ->join('etapas d', 'a.etapa = d.id')
                ->join('conceptos e', 'a.concepto = e.id')
                ->join('unidades_medida f', $joinUnidad)
                ->whereIn('a.".$q."idEmpresa".$q."', $empresas)
                ->where('a.".$q."fechaInicio".$q." >=', "{$from} 00:00:00")
                ->where('a.".$q."fechaInicio".$q." <=', "{$to} 23:59:59");

        // Pendientes (solo si se requiere)
        if ($pendientes) {
            $builder->groupStart()
                    ->where('a.".$q."porcAvanzado <', 100)
                    ->orWhere('a.porcAvanzado IS NULL', null, false)
                    ->groupEnd();
        }

        // Filtros dinámicos
        if ($empresa !== 0) {
            $builder->where("a.".$q."idEmpresa".$q."", $empresa);
        }

        if ($proyecto !== 0) {
            $builder->where("a.".$q."idProyecto".$q."", $proyecto);
        }

        if ($responsable !== 0) {
            $builder->where("a.".$q."idUsuario".$q."", $responsable);
        }

        return $builder->get()->getResultArray();
    }

    public function mdlTotalRegistros($desdeFecha, $hastaFecha) {

        $result = $this->db->table('actividades a')
                        ->selectCount('id', 'totalRegistros')
                        ->where('created_at >=', $desdeFecha)
                        ->where('created_at <=', $hastaFecha)
                        ->get()->getResultArray();

        return $result;
    }
}
