<?php

$routes->group('admin', function ($routes) {


    $routes->resource('tipos_proyecto', [
        'filter' => 'permission:tipos_proyecto-permission',
        'controller' => 'tipos_proyectoController',
        'except' => 'show',
        'namespace' => 'julio101290\boilerplateprojects\Controllers',
    ]);

    $routes->post('tipos_proyecto/save'
            , 'Tipos_proyectoController::save'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
    );
    $routes->post('tipos_proyecto/getTipos_proyecto'
            , 'Tipos_proyectoController::getTipos_proyecto'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
    );

    $routes->resource('proyectos', [
        'filter' => 'permission:proyectos-permission',
        'controller' => 'proyectosController',
        'except' => 'show',
        'namespace' => 'julio101290\boilerplateprojects\Controllers',
    ]);

    $routes->post('proyectos/save'
            , 'ProyectosController::save'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
    );

    $routes->post('proyectos/getProyectos'
            , 'ProyectosController::getProyectos'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
    );

    $routes->post('tiposProyectos/getTiposProyectosAjax'
            , 'Tipos_proyectoController::getTiposProyectoAjax'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->resource('etapas', [
        'filter' => 'permission:etapas-permission',
        'controller' => 'etapasController',
        'except' => 'show',
        'namespace' => 'julio101290\boilerplateprojects\Controllers',
    ]);

    $routes->post('etapas/save'
            , 'EtapasController::save'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );
    
    $routes->post('etapas/getEtapas'
            , 'EtapasController::getEtapas'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );
    
    
    $routes->post('etapas/getEtapasAjax'
            , 'EtapasController::getEtapasAjax'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );
    
    $routes->post('etapas/getEtapasActividadesAjax'
            , 'EtapasController::getEtapasAjaxActividad'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->resource('conceptos', [
        'filter' => 'permission:conceptos-permission',
        'controller' => 'conceptosController',
        'except' => 'show',
        'namespace' => 'julio101290\boilerplateprojects\Controllers',
    ]);
    $routes->post('conceptos/save'
            , 'ConceptosController::save'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );
    
    $routes->post('conceptos/getConceptos'
            , 'ConceptosController::getConceptos'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );
    $routes->post('conceptos/getConceptosAjax'
            , 'ConceptosController::getConceptosAjax'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );
    $routes->post('conceptos/getConceptosActividadesAjax'
            , 'ConceptosController::getConceptosActividadesAjax'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->resource('actividades', [
        'filter' => 'permission:actividades-permission',
        'controller' => 'actividadesController',
        'except' => 'show',
        'namespace' => 'julio101290\boilerplateprojects\Controllers',
    ]);
    
    $routes->post('actividades/save'
            , 'ActividadesController::save'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );
    
    $routes->post('actividades/getActividades'
            , 'ActividadesController::getActividades'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->get('actividades/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'
            , 'ActividadesController::actividadesFilters/$1/$2/$3/$4/$5/$6'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->get('actividadesCubo/(:any)/(:any)/(:any)/(:any)/(:any)/(:any)'
            , 'ActividadesController::actividadesCuboFilters/$1/$2/$3/$4/$5/$6'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->get('actividades/desdeCaja/(:any)'
            , 'ActividadesController::actividadesDesdeCaja/$1'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->post('proyecto/getProyectosAjax'
            , 'ProyectosController::getProyectosAjax'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->get('proyecto/cubo', 'ProyectosController::ctrCubo'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->resource('unidades_medida', [
        'filter' => 'permission:unidades_medida-permission',
        'controller' => 'unidades_medidaController',
        'except' => 'show',
        'namespace' => 'julio101290\boilerplateprojects\Controllers',
    ]);
    $routes->post('unidades_medida/save'
            , 'Unidades_medidaController::save'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );
    $routes->post('unidades_medida/getUnidades_medida'
            , 'Unidades_medidaController::getUnidades_medida'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->post('unidades_medida/getUnidadesAjax'
            , 'Unidades_medidaController::getUnidadesAjax'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->post('avanceActividad/save'
            , 'AvancesActividadesController::save'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->get('avanceActividad/getAvances/(:any)'
            , 'AvancesActividadesController::ctrGetAvances/$1'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->get('avanceActividad/delete/(:any)'
            , 'AvancesActividadesController::delete/$1'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

    $routes->get('proyectos/report/(:any)'
            , 'ProyectosController::report/$1'
            , ['namespace' => 'julio101290\boilerplateprojects\Controllers']
            );

   
});
