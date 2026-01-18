<?php
return [

    'pos' => [
        'label' => 'Ventas',
        'icon' => 'fas fa-shopping-cart',
        'route' => 'pos',
        'roles' => ['Admin', 'Employee', 'Seller'],
        'description' => 'Mòdulo de ventas, donde se agregan los productos en el carrito de compras.',
    ],
    'api' => [
        'label' => 'API FACTUS',
        'icon' => 'fas fa-plug',
        'route' => 'api',
        'roles' => ['Admin'],
        'description' => 'Mòdulo de API de Facturacion electronica de FACTUS colombia, creacion, visualizacion y busqueda de facturas.',
    ],
    'stock' => [
        'label' => 'Administración de Stock',
        'icon' => 'fas fa-boxes',
        'roles' => ['Admin','Employee'],
        'description' => 'Gestion de Categorias, Productos que se encuentran en el stock del sistema.',
        'children' => [
            'categories' => [
                'label' => 'Categorias',
                'icon' => 'fas fa-box',
                'route' => 'categories',
                'roles' => ['Admin'],
                'description' => 'Gestiòn de las categorias existentes en nuestro sistema utilizadas para categorizar nuestros productos.',
            ],
            'products' => [
                'label' => 'Productos',
                'icon' => 'fas fa-tags',
                'route' => 'products',
                'roles' => ['Admin', 'Employee'],
                'description' => 'Gestiòn de los productos existentes en nuestro sistema.',
            ],
        ],
    ],
    'Estadisticas' => [
        'label' => 'Reporteria',
        'icon' => 'fas fa-chart-pie',
        'roles' => ['Admin', 'Employee'],
        'description' => 'Reporteria de ventas realizadas en el sistema, estadisticas y graficas de las ventas',
        'children' => [
            'cashout' => [
                'label' => 'Cierre Caja',
                'icon' => 'fas fa-money-check-alt',
                'route' => 'cashout',
                'roles' => ['Admin','Employee'],
                'description' => 'Mòdulo de Cierre de caja de las ventas realizadas.',
            ],
            'reports' => [
                'label' => 'Reportes de Ventas',
                'icon' => 'fas fa-file-alt',
                'route' => 'reports',
                'roles' => ['Admin', 'Employee'],
                'description' => 'Mòdulo de Reportes de las ventas y sus estados actuales.',
            ],
            'graphics' => [
                'label' => 'Graficas y Estadisticas',
                'icon' => 'fas fa-chart-bar',
                'route' => 'graphics',
                'roles' => ['Admin'],
                'description' => 'Mòdulo de Graficas donde visualizamos la estadistica actual de nuestro sistema.',
            ],
        ],
    ],
    'Administración' => [
        'label' => 'Administración de Usuarios',
        'icon' => 'fas fa-users',
        'roles' => ['Admin'],
        'description' => 'Gestión de nuestros usuarios y privilegios del sistema.',
        'children' => [
            'users' => [
                'label' => 'Usuarios',
                'icon' => 'fas fa-user',
                'route' => 'users',
                'roles' => ['Admin'],
                'description' => 'Gestión de nuestros usuarios del sistema y su informaciòn',
            ],
            'roles' => [
                'label' => 'Roles',
                'icon' => 'fas fa-user-tag',
                'route' => 'roles',
                'roles' => ['Admin'],
                'description' => 'Mòdulo de gestion de roles existentes en nuestro sistema.',
            ],
            'permissions' => [
                'label' => 'Permisos',
                'icon' => 'fas fa-user-lock',
                'route' => 'permisos',
                'roles' => ['Admin'],
                'description' => 'Gestión de permisos de nuestros roles existentes.',
            ],
            'assign' => [
                'label' => 'Asignar Permisos',
                'icon' => 'fas fa-check-square',
                'route' => 'asignar',
                'roles' => ['Admin'],
                'description' => 'Gestión de asignaciòn de permisos a nuestros roles existentes.',
            ],
            'modules' => [
                'label' => 'Asignar Modulos',
                'icon' => 'fas fa-user-lock',
                'route' => 'modules',
                'roles' => ['Admin'],
                'description' => 'Gestión de asignaciòn de modulos.',
            ],
        ],
    ],
    'Configuracion' => [
        'label' => 'Configuración',
        'icon' => 'fas fa-cogs',
        'roles' => ['Admin'],
        'description' => 'Configuracion de parametros del sistema, informacion del sistema, moneda utilizada en el sistema.',
        'children' => [
            'config' => [
                'label' => 'Configuraciones del sistema',
                'icon' => 'fas fa-sliders-h',
                'route' => 'config',
                'roles' => ['Admin'],
                'description' => 'Administraciòn de los parametros existentes en nuestro sistema.',
            ],
            'companies' => [
                'label' => 'Compañias',
                'icon' => 'fas fa-id-card',
                'route' => 'companies',
                'roles' => ['Admin'],
                'description' => 'Administraciòn de la informaciòn de nuestro sistema.',
            ],
            'denominations' => [
                'label' => 'Monedas',
                'icon' => 'fas fa-money-bill-wave',
                'route' => 'coins',
                'roles' => ['Admin'],
                'description' => 'Administraciòn de los tipos de monedas utilizados en nuestro sistema.',
            ],
        ],
    ],
    /*'configuracion' => [
        'label' => 'Configuraciones',
        'icon' => 'fas fa-cogs',
        'route' => 'configuracion',
        'roles' => ['Admin'],
    ],*/
];



