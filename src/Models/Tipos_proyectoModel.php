<?php
namespace App\Models;
use CodeIgniter\Model;
class Tipos_proyectoModel extends Model{
    protected $table      = 'tipos_proyecto';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType     = 'array';
    protected $useSoftDeletes = true;
    protected $allowedFields = ['id','idEmpresa','descripcion','created_at','deleted_at','updated_at'];
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $deletedField  = 'deleted_at';
    protected $validationRules    =  [
    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;



    public function mdlGetTipos_proyecto($idEmpresas){

        $result = $this->db->table('tipos_proyecto a, empresas b')
                 ->select('a.id,a.idEmpresa,a.descripcion,a.created_at,a.deleted_at,a.updated_at ,b.nombre as nombreEmpresa')
                 ->where('a.idEmpresa', 'b.id', FALSE)
                 ->whereIn('a.idEmpresa',$idEmpresas);
 
         return $result;
     }

}
        