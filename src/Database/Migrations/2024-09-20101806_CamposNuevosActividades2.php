<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CamposNuevosActividades2 extends Migration {

    public function up() {

        $campos = [
            'porcAvanzado' => ['type' => 'tinyint', 'constraint' => 4, 'null' => true],
        ];

        $this->forge->addColumn('actividades', $campos);
    }

    public function down() {
        //cd .
    }
}
