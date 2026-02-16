<?php
// app/helpers.php o directamente en AppServiceProvider si no tienes un archivo de helpers
use Illuminate\Support\Facades\Auth;

use App\Models\Module;


function getUserModules()
{
    $user = Auth::user();
    if (! $user) return [];

    // active por key
    $dbModules = Module::pluck('active', 'key')->toArray();
    $configModules = config('modules');

    $filterByRoles = fn ($item) =>
        empty($item['roles']) || $user->hasAnyRole($item['roles']);


    $result = [];

    foreach ($configModules as $key => $module) {

        // 🔒 módulo padre inactivo
        if (!($dbModules[$key] ?? false)) {
            continue;
        }

        // 👶 hijos
        $children = [];

        if (!empty($module['children'])) {
            foreach ($module['children'] as $childKey => $child) {

                if (!($dbModules[$childKey] ?? false)) continue;
                if (!$filterByRoles($child)) continue;

                $children[$childKey] = $child;
            }
        }

        // 👤 roles del padre
        if (!$filterByRoles($module) && empty($children)) {
            continue;
        }

        // 🧠 flags
        $module['children'] = $children;
        $module['has_children'] = count($children) > 0;

        // 🚦 rutas
        $module['route'] = $module['has_children']
            ? route('modules.show', $key)
            : ($module['route'] ?? null);

        $result[$key] = $module;
    }

    return $result;
}


/*function getUserModules(): array
{
    $user = Auth::user();
    if (!$user) return [];

    $roles = $user->getRoleNames()->toArray();
    $configModules = config('modules');

    // módulos activos en BD
    $activeModules = Module::where('active', 1)
        ->pluck('key')
        ->toArray();

    $result = [];

    foreach ($configModules as $key => $module) {

        // 🔴 inactivo en BD
        if (!in_array($key, $activeModules)) {
            continue;
        }

        // 🔒 rol
        if (!empty($module['roles']) && !array_intersect($roles, $module['roles'])) {
            continue;
        }

        $result[$key] = $module;
    }

    return $result;
}*/


/*function getUserModules()
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
*/
