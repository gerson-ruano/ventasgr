<?php

namespace App\Livewire;

use App\Models\Module;
use Livewire\Component;

class Admodules extends Component
{
    public function render()
    {
        return view('livewire.modules.components', [
            'tree' => $this->getModuleTree(),
        ])->extends('layouts.app')
            ->section('content');
    }
    protected function getModuleTree()
    {
        $dbModules = Module::pluck('active', 'key')->toArray();
        $configModules = config('modules');

        $result = [];

        foreach ($configModules as $key => $module) {
            $result[] = [
                'key'         => $key,
                'label'       => $module['label'] ?? ucfirst($key),
                'icon'        => $module['icon'] ?? null,
                'description' => $module['description'] ?? null,
                'active'      => $dbModules[$key] ?? false,
                'children'    => collect($module['children'] ?? [])
                    ->map(fn($child, $childKey) => [
                        'key'         => $childKey,
                        'label'       => $child['label'] ?? ucfirst($childKey),
                        'icon'        => $child['icon'] ?? null,
                        'description' => $child['description'] ?? null,
                        'active'      => $dbModules[$childKey] ?? false,
                    ])
                    ->values(),
            ];
        }
        return $result;
    }
    public function toggle($key)
    {
        $module = Module::where('key', $key)->firstOrFail();
        $module->update(['active' => ! $module->active]);

        $this->dispatch('notify', 'Estado actualizado');
    }
}
