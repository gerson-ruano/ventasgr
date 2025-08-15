<?php
return [


    'pos' => [
        'label' => 'Ventas',
        'icon' => 'fas fa-shopping-cart',
        'route' => 'pos',
        'roles' => ['Admin', 'Employee', 'Seller'],
    ],
    'api' => [
        'label' => 'API FACTUS',
        'icon' => 'fas fa-plug',
        'route' => 'api',
        'roles' => ['Admin'],
    ],
    'stock' => [
        'label' => 'Administración de Stock',
        'icon' => 'fas fa-boxes',
        'roles' => ['Admin ','Employee'],
        'children' => [
            'categories' => [
                'label' => 'Categorias',
                'icon' => 'fas fa-box',
                'route' => 'categories',
                'roles' => ['Admin'],
            ],
            'products' => [
                'label' => 'Productos',
                'icon' => 'fas fa-tags',
                'route' => 'products',
                'roles' => ['Admin', 'Employee'],
            ],
        ],
    ],
    'graphics' => [
        'label' => 'Reporteria',
        'icon' => 'fas fa-chart-pie',
        'roles' => ['Admin', 'Employee'],
        'children' => [
            'cashout' => [
                'label' => 'Cierre Caja',
                'icon' => 'fas fa-money-check-alt',
                'route' => 'cashout',
                'roles' => ['Admin','Employee'],
            ],
            'reports' => [
                'label' => 'Reportes de Ventas',
                'icon' => 'fas fa-file-alt',
                'route' => 'reports',
                'roles' => ['Admin', 'Employee'],
            ],
            'graphics' => [
                'label' => 'Graficas y Estadisticas',
                'icon' => 'fas fa-chart-bar',
                'route' => 'graphics',
                'roles' => ['Admin'],
            ],
        ],
    ],
    'Administración' => [
        'label' => 'Administración de Usuarios',
        'icon' => 'fas fa-users',
        'roles' => ['Admin'],
        'children' => [
            'users' => [
                'label' => 'Usuarios',
                'icon' => 'fas fa-user',
                'route' => 'users',
                'roles' => ['Admin'],
            ],
            'roles' => [
                'label' => 'Roles',
                'icon' => 'fas fa-user-tag',
                'route' => 'roles',
                'roles' => ['Admin'],
            ],
            'permissions' => [
                'label' => 'Permisos',
                'icon' => 'fas fa-user-lock',
                'route' => 'permisos',
                'roles' => ['Admin'],
            ],
            'assign' => [
                'label' => 'Asignar Permisos',
                'icon' => 'fas fa-check-square',
                'route' => 'asignar',
                'roles' => ['Admin'],
            ],
        ],
    ],
    'Configuracion' => [
        'label' => 'Configuración',
        'icon' => 'fas fa-cogs',
        'roles' => ['Admin'],
        'children' => [
            'config' => [
                'label' => 'Configuraciones del sistema',
                'icon' => 'fas fa-sliders-h',
                'route' => 'config',
                'roles' => ['Admin'],
            ],
            'companies' => [
                'label' => 'Compañias',
                'icon' => 'fas fa-id-card',
                'route' => 'companies',
                'roles' => ['Admin'],
            ],
            'denominations' => [
                'label' => 'Monedas',
                'icon' => 'fas fa-money-bill-wave',
                'route' => 'coins',
                'roles' => ['Admin'],
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



