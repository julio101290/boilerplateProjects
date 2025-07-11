<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CamposNuevosActividades4 extends Migration {



    public function up() {

        $campos = [
            'idProveedor' => ['type' => 'bigint', 'constraint' => 20, 'null' => true],
            'modalidadActividad' => ['type' => 'varchar', 'constraint' => 4, 'null' => true],
        ];

        $this->forge->addColumn('actividades', $campos);
        
        
       
        
        
    }

    public function down() {
        //cd .
    }
}
