<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class Actividades extends Migration
{
    public function up()
    {
        // Actividades
        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'idEmpresa' => ['type' => 'int', 'constraint' => 11, 'null' => true],
            'idProyecto' => ['type' => 'bigint', 'constraint' => 20, 'null' => true],
            'etapa' => ['type' => 'bigint', 'constraint' => 20, 'null' => true],
            'concepto' => ['type' => 'bigint', 'constraint' => 20, 'null' => true],
            'descripcion' => ['type' => 'varchar', 'constraint' => 1024, 'null' => true],
            'fechaInicio' => ['type' => 'datetime', 'null' => true],
            'fechaFinal' => ['type' => 'datetime', 'null' => false],
            'cantEstimada' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'null' => true],
            'cantReal' => ['type' => 'DECIMAL', 'constraint' => '18,2', 'null' => true],
            'unidadMedida' => ['type' => 'varchar', 'constraint' => 16, 'null' => false],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('actividades', true);
    }

    public function down()
    {
        $this->forge->dropTable('actividades', true);
    }
}
