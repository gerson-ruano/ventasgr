<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;

class DepartamentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Departamento::truncate();

        $departamentos = [
            'Alta Verapaz', 'Baja Verapaz', 'Chimaltenango', 'Chiquimula',
            'El Progreso', 'Escuintla', 'Guatemala', 'Huehuetenango',
            'Izabal', 'Jalapa', 'Jutiapa', 'Petén', 'Quetzaltenango',
            'Quiché', 'Retalhuleu', 'Sacatepéquez', 'San Marcos',
            'Santa Rosa', 'Sololá', 'Suchitepéquez', 'Totonicapán',
            'Zacapa'
        ];

        foreach ($departamentos as $nombre) {
            Departamento::create(['nombre' => $nombre]);
        }
    }
}
