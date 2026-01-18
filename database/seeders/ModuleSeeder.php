<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Module;
use Illuminate\Support\Facades\DB;

class ModuleSeeder extends Seeder
{
    public function run(): void

    {
        /*DB::statement('SET FOREIGN_KEY_CHECKS=0;'); // Desactivar llaves foráneas (opcional, si hay relaciones)
        Module::truncate(); // Elimina todo y reinicia los IDs
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');*/

        $modulos = config('modules');

        foreach ($modulos as $key => $module) {

            // 👉 CREAR MÓDULO PADRE
            $parent = Module::firstOrCreate(
                ['key' => $key],
                [
                    'name' => $module['label'] ?? ucfirst($key),
                    'description' => $module['description'] ?? null,
                    'active' => true,
                ]
            );

            // 👉 CREAR SUBMÓDULOS
            if (!empty($module['children'])) {
                foreach ($module['children'] as $childKey => $child) {

                    Module::firstOrCreate(
                        ['key' => $childKey],
                        [
                            'name' => $child['label'] ?? ucfirst($childKey),
                            'description' => $child['description'] ?? null,
                            'active' => true,
                        ]
                    );
                }
            }
        }
    }
}
