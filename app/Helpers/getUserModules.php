<?php
// app/helpers.php o directamente en AppServiceProvider si no tienes un archivo de helpers
use Illuminate\Support\Facades\Auth;

function getUserModules()
{
    $modules = config('modules');
    $user = Auth::user();

    if (!$user) {
        return [];
    }

    $filterByRoles = function ($item) use ($user) {
        return empty($item['roles']) || ($user?->hasAnyRole($item['roles']) ?? false);
    };

    foreach ($modules as $key => &$module) {
        if (isset($module['children'])) {
            $module['children'] = array_filter($module['children'], $filterByRoles);
        }

        if (!$filterByRoles($module) && empty($module['children'])) {
            unset($modules[$key]);
        }
    }

    return $modules;
}
