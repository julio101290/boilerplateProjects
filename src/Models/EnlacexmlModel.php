<?php

namespace julio101290\boilerplatesells\Models;

use CodeIgniter\Model;

class EnlacexmlModel extends Model {

    protected $table = 'enlacexml';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id', 'idDocumento', 'uuidXML', 'tipo', 'importe', 'created_at', 'updated_at', 'deleted_at'];
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $deletedField = 'deleted_at';
    protected $validationRules = [
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function mdlGetEnlacexml($idEmpresas) {

        $result = $this->db->table('enlacexml a, empresas b')
                ->select('a.id,a.idDocumento,a.uuidXML,a.tipo,a.importe,a.created_at,a.updated_at,a.deleted_at ,b.nombre as nombreEmpresa')
                ->where('a.idEmpresa', 'b.id', FALSE)
                ->whereIn('a.idEmpresa', $idEmpresas);

        return $result;
    }

    /**
     * Obtener datos de XML por enlace
     * @param type $idEmpresas
     * @return type
     */
    public function mdlGetEnlacexml2($idVenta) {

        $request = service('request');
        $db = \Config\Database::connect();

        $columns = ['a.id', 'a.idDocumento', 'a.uuidXML', 'a.tipo', 'a.importe', 'c.status', 'c.archivoXML', 'a.created_at', 'a.updated_at', 'a.deleted_at'];

        // === FROM y JOIN ===
        $builder = $db->table('enlacexml a');
        $builder->join('xml c', 'c.uuidTimbre = a.uuidXML');

        // === WHERE principal ===
        $builder->where('a.idDocumento', $idVenta);

        // === Total sin filtro ===
        $total = $builder->countAllResults(false); // no reset
        // === Búsqueda global ===
        $searchValue = $request->getPost('search')['value'] ?? '';
        if ($searchValue) {
            $builder->groupStart();
            foreach ($columns as $col) {
                $builder->orLike($col, $searchValue);
            }
            $builder->groupEnd();
        }

        // === Total filtrado ===
        $filtered = $builder->countAllResults(false);

        // === Ordenamiento ===
        $orderColumnIndex = $request->getPost('order')[0]['column'] ?? 0;
        $orderColumn = $columns[$orderColumnIndex] ?? 'a.id';
        $orderDir = $request->getPost('order')[0]['dir'] ?? 'asc';
        $builder->orderBy($orderColumn, $orderDir);

        // === Paginación ===
        $length = $request->getPost('length') ?? 10;
        $start = $request->getPost('start') ?? 0;
        $builder->limit($length, $start);

        // === Ejecutar y devolver ===
        $query = $builder->get();
        $data = $query->getResultArray();

        return $this->response->setJSON([
                    'draw' => intval($request->getPost('draw')),
                    'recordsTotal' => $total,
                    'recordsFiltered' => $filtered,
                    'data' => $data
        ]);
    }

    /**
     * Obtener datos de XML por enlace en Arreglo
     * @param type $idEmpresas
     * @return type
     */
    public function mdlGetEnlacexmlDatos($idVenta) {

        $result = $this->db->table('enlacexml a, xml c')
                ->select('a.id
                         ,a.idDocumento
                         ,a.uuidXML
                         ,a.tipo
                         ,a.importe
                         ,c.status
                         ,c.archivoXML
                         ,a.created_at
                         ,a.updated_at
                         ,a.deleted_at')
                ->where('a.idDocumento', $idVenta)
                ->where('c.uuidTimbre', 'a.uuidXML', FALSE);

        return $result;
    }

    /**
     * Obtener datos de XML para saber si la venta tiene factura
     * @param type $idEmpresas
     * @return type
     */
    public function mdlGetVentaTieneFactura($idVenta) {
        $builder = $this->db->table('enlacexml a');

        $builder->join('xml c', 'c.uuidTimbre = a.uuidXML');

        $builder->select('COUNT(*) AS total')
                ->where('a.idDocumento', $idVenta)
                ->where('a.tipo', 'ven')
                ->where("COALESCE(c.status, '') <>", 'cancelado');

        $query = $builder->get();
        $row = $query->getRow();

        return $row ? (int) $row->total : 0;
    }

    /**
     * Verifica si la nota de credito tiene esta facturada
     * @param type $idEmpresas
     * @return type
     */
    public function mdlGetVentaTieneFacturaNotaCredito($idNotaCredito) {

        $result = $this->db->table('enlacexml a, xml c')
                        ->select('*')
                        ->where('a.idDocumento', $idNotaCredito)
                        ->where('a.tipo', 'NCR')
                        ->where("ifnull(c.status,'')<>", "cancelado")
                        ->where('c.uuidTimbre', 'a.uuidXML', FALSE)->countAllResults();

        return $result;
    }
}
