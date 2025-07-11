<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CamposNuevosActividades extends Migration {

    public function up() {

        $campos = [
            'status' => ['type' => 'varchar', 'constraint' => 2, 'null' => true],
        ];

        $this->forge->addColumn('actividades', $campos);
    }

    public function down() {
        //cd .
    }
}
