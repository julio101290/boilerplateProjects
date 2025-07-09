<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Etapas extends Migration {

    public function up() {
        // Etapas
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'idEmpresa' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'descripcion' => ['type' => 'varchar', 'constraint' => 128, 'null' => true],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('etapas', true);
    }

    public function down() {
        $this->forge->dropTable('etapas', true);
    }
}
