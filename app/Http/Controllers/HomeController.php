<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Module;
use Illuminate\Support\Facades\Config;


class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        //$userRoles = $user->getRoleNames()->toArray(); // ['Admin'], ['Seller'], etc.
        $roles = $user->getRoleNames()->toArray();

        $allModules = Config::get('modules');
        $modules = [];

        foreach ($allModules as $key => $module) {
            if (isset($module['roles']) && array_intersect($roles, $module['roles'])) {
                $modules[$key] = [
                    'label' => $module['label'] ?? ucfirst($key),
                    'icon' => $module['icon'] ?? 'fas fa-cube',
                    'description' => $module['description'] ?? '',
                    'route' => isset($module['children'])
                        ? route('modules.show', ['module' => $key])
                        : route($module['route'] ?? $key),
                    'has_children' => isset($module['children']),
                ];
            }
        }

        return view('livewire.pages.home.modules', compact('modules'));
    }

    public function showModule($module)
    {
        $user = Auth::user();
        $roles = $user->getRoleNames()->toArray();
        $config = Config::get("modules.$module");

        abort_unless($config && isset($config['children']), 404);

        $children = [];

        foreach ($config['children'] as $key => $child) {
            if (array_intersect($roles, $child['roles'])) {
                $children[] = [
                    'label' => $child['label'] ?? ucfirst($key),
                    'icon' => $child['icon'] ?? 'fas fa-cube',
                    'description' => $child['description'] ?? '',
                    'route' => route($child['route']),
                ];
            }
        }

        return view('livewire.pages.home.menus', [
            'moduleLabel' => $config['label'] ?? ucfirst($module),
            'children' => $children,
        ]);
    }
}
