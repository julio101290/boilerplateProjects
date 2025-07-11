<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Avance_actividad extends Migration {

    public function up() {
        // Avance_actividad
        $this->forge->addField([
                'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'idActividad' => ['type' => 'bigint', 'constraint' => 20, 'null' => true],
                'fecha' => ['type' => 'datetime', 'null'  => true],
                'descripcion'  => ['type' => 'varchar', 'constraint'  => 256, 'null'  => true],
                'porcentaje'  => ['type' => 'tinyint', 'constraint'  => 4, 'null'  => true],
                'horas'  => ['type' => 'float', 'null'  => true],
                'created_at'  => ['type' => 'datetime', 'null'  => true],
                'updated_at'  => ['type' => 'datetime', 'null'  => true],
                'deleted_at'  => ['type' => 'datetime', 'null'  => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('avance_actividad', true);
    }

    public function down() {
        $this->forge->dropTable('avance_actividad', true);
    }
}
