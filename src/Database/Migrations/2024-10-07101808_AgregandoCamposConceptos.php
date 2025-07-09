<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AgregandoCamposEtapas extends Migration {

    public function up() {

        $campos = [
            'orden' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'tipoProyecto' => ['type' => 'bigint', 'constraint' => 20, 'null' => true],
        ];

    
        $this->forge->addColumn('etapas', $campos);
    }

    public function down() {
        //cd .
    }
}
