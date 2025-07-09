<?php

namespace julio101290\boilerplateprojects\Database\Seeds;

use CodeIgniter\Config\Services;
use CodeIgniter\Database\Seeder;
use Myth\Auth\Entities\User;
use Myth\Auth\Models\UserModel;

/**
 * Class BoilerplateSeeder.
 */
class BoilerplateProjects extends Seeder {

    /**
     * @var Authorize
     */
    protected $authorize;

    /**
     * @var Db
     */
    protected $db;

    /**
     * @var Users
     */
    protected $users;

    public function __construct() {
        $this->authorize = Services::authorization();
        $this->db = \Config\Database::connect();
        $this->users = new UserModel();
    }

    public function run() {


        // Permission
        $this->authorize->createPermission('tipos_proyecto-permission', 'Permission for CRUD Types of Projects');

        // Assign Permission to user
        $this->authorize->addPermissionToUser('tipos_proyecto-permission', 1);

        // Permission
        $this->authorize->createPermission('proyectos-permission', 'Permission for Projects CRUD');

        // Assign Permission to user
        $this->authorize->addPermissionToUser('proyectos-permission', 1);

        // Permission
        $this->authorize->createPermission('etapas-permission', 'Permission for stages CRUD');

        // Assign Permission to user
        $this->authorize->addPermissionToUser('etapas-permission', 1);
        
          // Permission
        $this->authorize->createPermission('conceptos-permission', 'Permission for concepts CRUD');

        // Assign Permission to user
        $this->authorize->addPermissionToUser('conceptos-permission', 1);
    }

    public function down() {
        //
    }
}
