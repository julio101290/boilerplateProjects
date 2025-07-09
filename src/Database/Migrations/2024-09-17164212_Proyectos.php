<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Proyectos extends Migration {

    public function up() {
        // Proyectos
        $this->forge->addField([
                'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
                'idEmpresa' => ['type' => 'int', 'constraint' => 11, 'null' => true],
                'idSucursal' => ['type' => 'int', 'constraint' => 11, 'null' => true],
                'tipoProyecto' => ['type' => 'int', 'constraint' => 11, 'null' => true],
                'fechaInicio' => ['type' => 'datetime',  'null'  => true],
                'fechaFinal'  => ['type' => 'datetime', 'null'  => true],
                'idCliente'  => ['type' => 'int', 'constraint'  => 11, 'null'  => true],
                'responsable'  => ['type' => 'varchar', 'constraint'  => 256, 'null'  => true],
                'created_at'  => ['type' => 'datetime', 'null'  => true],
                'updated_at'  => ['type' => 'datetime', 'null'  => true],
                'deleted_at'  => ['type' => 'datetime', 'null'  => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('proyectos', true);
    }

    public function down() {
        $this->forge->dropTable('proyectos', true);
    }
}
