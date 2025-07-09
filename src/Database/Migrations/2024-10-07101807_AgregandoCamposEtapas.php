<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AgregandoCamposConceptos extends Migration {

    public function up() {

        $campos = [
            'orden' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'tipoProyecto' => ['type' => 'bigint', 'constraint' => 20, 'null' => true],
        ];

    
        $this->forge->addColumn('conceptos', $campos);
    }

    public function down() {
        //cd .
    }
}
