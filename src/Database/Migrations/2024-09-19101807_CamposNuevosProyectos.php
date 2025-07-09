<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CamposNuevosProyectos extends Migration {

    public function up() {

        $campos = [
            'descripcion' => ['type' => 'varchar', 'constraint' => 64, 'null' => true],
        ];

        $this->forge->addColumn('proyectos', $campos);
    }

    public function down() {
        //cd .
    }
}
