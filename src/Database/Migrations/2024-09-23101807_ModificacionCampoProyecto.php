<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ModificacionCampoProyecto extends Migration {

    public function up() {

        $campos = [
            'responsable' => ['type' => 'bigint', 'constraint' => 20, 'null' => true],
        ];

        $this->forge->dropColumn('proyectos', ['responsable']);

        $this->forge->addColumn('proyectos', $campos);
    }

    public function down() {
        //cd .
    }
}
