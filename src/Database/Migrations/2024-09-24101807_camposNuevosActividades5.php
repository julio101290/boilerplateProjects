<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModificacionCampoActividades5 extends Migration {

    public function up() {

        $campos = [
            'costoUnitario' => ['type' => 'decimal',  'constraint' => '18,6', 'null' => true],
            'costoTotalEstimado' => ['type' => 'decimal', 'constraint' => '18,6', 'null' => true],
            'costoTotalReal' => ['type' => 'decimal',  'constraint' => '18,6', 'null' => true],
            'producto' => ['type' => 'bigint', 'constraint' => 20, 'null' => true],
        ];

        $this->forge->addColumn('actividades', $campos);
    }

    public function down() {
        //cd .
    }
}
