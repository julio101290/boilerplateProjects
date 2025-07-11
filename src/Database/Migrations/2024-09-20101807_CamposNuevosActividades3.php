<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CamposNuevosActividades3 extends Migration {

    public function up() {

        $campos = [
            'idUsuario' => ['type' => 'bigint', 'constraint' => 20, 'null' => true],
        ];

        $this->forge->addColumn('actividades', $campos);
    }

    public function down() {
        //cd .
    }
}
