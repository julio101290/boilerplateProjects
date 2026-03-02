<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CamposNuevosProyectos20260302 extends Migration {

    public function up() {

        $campos = [
            'status' => ['type' => 'varchar', 'constraint' => 1, 'null' => true],
        ];

        $this->forge->addColumn('proyectos', $campos);
    }

    public function down() {
        //cd .
    }
}
