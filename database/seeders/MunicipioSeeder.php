<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Departamento;
use App\Models\Municipio;

class MunicipioSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Limpiar la tabla antes de insertar (opcional)
        #Municipio::truncate();

        // Obtener todos los departamentos
        $departamentos = Departamento::pluck('id', 'nombre');

        // Alta Verapaz (17 municipios) [citation:5]
        $altaVerapaz = $departamentos['Alta Verapaz'];
        $municipiosAltaVerapaz = [
            'Chahal', 'Chisec', 'Cobán', 'Fray Bartolomé de las Casas', 'La Tinta',
            'Lanquín', 'Panzós', 'Raxruhá', 'San Cristóbal Verapaz', 'San Juan Chamelco',
            'San Pedro Carchá', 'Santa Cruz Verapaz', 'Cahabón', 'Senahú', 'Tamahú',
            'Tactic', 'Tucurú'
        ];

        // Baja Verapaz (8 municipios) [citation:3][citation:5]
        $bajaVerapaz = $departamentos['Baja Verapaz'];
        $municipiosBajaVerapaz = [
            'Cubulco', 'Granados', 'Purulhá', 'Rabinal', 'Salamá', 'San Jerónimo',
            'San Miguel Chicaj', 'Santa Cruz el Chol'
        ];

        // Chimaltenango (16 municipios) [citation:5]
        $chimaltenango = $departamentos['Chimaltenango'];
        $municipiosChimaltenango = [
            'Acatenango', 'Chimaltenango', 'El Tejar', 'Parramos', 'Patzicía',
            'Patzún', 'Pochuta', 'San Andrés Itzapa', 'San José Poquíl',
            'San Juan Comalapa', 'San Martín Jilotepeque', 'Santa Apolonia',
            'Santa Cruz Balanyá', 'Tecpán Guatemala', 'Yepocapa', 'Zaragoza'
        ];

        // Chiquimula (11 municipios) [citation:5]
        $chiquimula = $departamentos['Chiquimula'];
        $municipiosChiquimula = [
            'Camotán', 'Chiquimula', 'Concepción Las Minas', 'Esquipulas', 'Ipala',
            'Olopa', 'Quetzaltepeque', 'San Jacinto', 'San José La Arada',
            'San Juan Ermita', 'Jocotán'
        ];

        // El Progreso (8 municipios) [citation:5]
        $elProgreso = $departamentos['El Progreso'];
        $municipiosElProgreso = [
            'El Jícaro', 'Guastatoya', 'Morazán', 'San Agustín Acasaguastlán',
            'San Antonio La Paz', 'San Cristóbal Acasaguastlán', 'Sanarate', 'Sansare'
        ];

        // Escuintla (14 municipios) [citation:5]
        $escuintla = $departamentos['Escuintla'];
        $municipiosEscuintla = [
            'Escuintla', 'Guanagazapa', 'Iztapa', 'La Democracia', 'La Gomera',
            'Masagua', 'Nueva Concepción', 'Palín', 'San José', 'San Vicente Pacaya',
            'Santa Lucía Cotzumalguapa', 'Sipacate', 'Siquinalá', 'Tiquisate'
        ];

        // Guatemala (17 municipios) [citation:5][citation:6]
        $guatemala = $departamentos['Guatemala'];
        $municipiosGuatemala = [
            'Amatitlán', 'Chinautla', 'Chuarrancho', 'Guatemala City', 'Fraijanes',
            'Mixco', 'Palencia', 'San José del Golfo', 'San José Pinula',
            'San Juan Sacatepéquez', 'San Miguel Petapa', 'San Pedro Ayampuc',
            'San Pedro Sacatepéquez', 'San Raymundo', 'Santa Catarina Pinula',
            'Villa Canales', 'Villa Nueva'
        ];

        // Huehuetenango (33 municipios) [citation:3][citation:5]
        $huehuetenango = $departamentos['Huehuetenango'];
        $municipiosHuehuetenango = [
            'Aguacatán', 'Chiantla', 'Colotenango', 'Concepción Huista', 'Cuilco',
            'Huehuetenango', 'Jacaltenango', 'La Democracia', 'La Libertad',
            'Malacatancito', 'Nentón', 'Petatán', 'San Antonio Huista',
            'San Gaspar Ixchil', 'San Ildefonso Ixtahuacán', 'San Juan Atitán',
            'San Juan Ixcoy', 'San Mateo Ixtatán', 'San Miguel Acatán',
            'San Pedro Nécta', 'San Pedro Soloma', 'San Rafael La Independencia',
            'San Rafael Pétzal', 'San Sebastián Cotán', 'San Sebastián Huehuetenango',
            'Santa Ana Huista', 'Santa Bárbara', 'Santa Cruz Barillas',
            'Santa Eulalia', 'Santiago Chimaltenango', 'Tectitán',
            'Todos Santos Cuchumatán', 'Unión Cantinil'
        ];

        // Izabal (5 municipios) [citation:3][citation:5]
        $izabal = $departamentos['Izabal'];
        $municipiosIzabal = [
            'El Estor', 'Livingston', 'Los Amates', 'Morales', 'Puerto Barrios'
        ];

        // Jalapa (7 municipios) [citation:5]
        $jalapa = $departamentos['Jalapa'];
        $municipiosJalapa = [
            'Jalapa', 'Mataquescuintla', 'Monjas', 'San Carlos Alzatate',
            'San Luis Jilotepeque', 'San Manuel Chaparrón', 'San Pedro Pinula'
        ];

        // Jutiapa (17 municipios) [citation:3][citation:5]
        $jutiapa = $departamentos['Jutiapa'];
        $municipiosJutiapa = [
            'Agua Blanca', 'Asunción Mita', 'Atescatempa', 'Comapa', 'Conguaco',
            'El Adelanto', 'El Progreso', 'Jalpatagua', 'Jutiapa', 'Moyuta',
            'Pasaco', 'Quesada', 'San José Acatempa', 'Santa Catarina Mita',
            'Yupiltepeque', 'Zapotitlán', 'Santa María Ixhuatán'
        ];

        // Petén (14 municipios) [citation:3][citation:5]
        $peten = $departamentos['Petén'];
        $municipiosPeten = [
            'Dolores', 'El Chal', 'Flores', 'La Libertad', 'Las Cruces',
            'Melchor de Mencos', 'Poptún', 'San Andrés', 'San Benito',
            'San Francisco', 'San José', 'San Luis', 'Santa Ana', 'Sayaxché'
        ];

        // Quetzaltenango (24 municipios) [citation:3][citation:5]
        $quetzaltenango = $departamentos['Quetzaltenango'];
        $municipiosQuetzaltenango = [
            'Almolonga', 'Cabricán', 'Cajolá', 'Cantel', 'Coatepeque',
            'Colomba', 'Concepción Chiquirichapa', 'El Palmar', 'Flores Costa Cuca',
            'Génova', 'Huitán', 'La Esperanza', 'Olintepeque', 'Palestina de Los Altos',
            'Quetzaltenango', 'Salcajá', 'San Carlos Sija', 'San Francisco La Unión',
            'San Martín Sacatepéquez', 'San Mateo', 'San Miguel Sigüilá', 'Sibilia',
            'Zunil', 'Cabricán'
        ];

        // Quiché (21 municipios) [citation:3][citation:5]
        $quiche = $departamentos['Quiché'];
        $municipiosQuiche = [
            'Canillá', 'Chajul', 'Chichicastenango', 'Chiché', 'Chinique',
            'Cunén', 'Ixcán', 'Joyabaj', 'Nebaj', 'Pachalum', 'Patzité',
            'Playa Grande Ixcán', 'Sacapulas', 'San Andrés Sajcabajá',
            'San Antonio Ilotenango', 'San Bartolomé Jocotenango',
            'San Juan Cotzal', 'San Pedro Jocopilas', 'Santa Cruz del Quiché',
            'Uspantán', 'Zacualpa'
        ];

        // Retalhuleu (9 municipios) [citation:3][citation:5]
        $retalhuleu = $departamentos['Retalhuleu'];
        $municipiosRetalhuleu = [
            'Champerico', 'El Asintal', 'Nuevo San Carlos', 'Retalhuleu',
            'San Andrés Villa Seca', 'San Felipe', 'San Martín Zapotitlán',
            'San Sebastián', 'Santa Cruz Muluá'
        ];

        // Sacatepéquez (16 municipios) [citation:3][citation:5]
        $sacatepequez = $departamentos['Sacatepéquez'];
        $municipiosSacatepequez = [
            'Antigua Guatemala', 'Ciudad Vieja', 'Jocotenango', 'Magdalena Milpas Altas',
            'Pastores', 'San Antonio Aguas Calientes', 'San Bartolomé Milpas Altas',
            'San Juan Alotenango', 'San Lucas Sacatepéquez', 'San Miguel Dueñas',
            'Santa Catarina Barahona', 'Santa Lucía Milpas Altas', 'Santa María de Jesús',
            'Santiago Sacatepéquez', 'Santo Domingo Xenacoj', 'Sumpango'
        ];

        // San Marcos (30 municipios) [citation:3][citation:5]
        $sanMarcos = $departamentos['San Marcos'];
        $municipiosSanMarcos = [
            'Ayutla', 'Catarina', 'Comitancillo', 'Concepción Tutuapa', 'El Quetzal',
            'El Rodeo', 'El Tumbador', 'Ixchiguán', 'La Blanca', 'La Reforma',
            'Malacatán', 'Nuevo Progreso', 'Ocós', 'Pajapita', 'Esquipulas Palo Gordo',
            'San Antonio Sacatepéquez', 'San Cristóbal Cucho', 'San José El Rodeo',
            'San José Ojetenam', 'San Lorenzo', 'San Marcos', 'San Miguel Ixtahuacán',
            'San Pablo', 'San Pedro Sacatepéquez', 'San Rafael Pie de la Cuesta',
            'Sibinal', 'Sipacapa', 'Tacanal', 'Tajumulco', 'Tejutla'
        ];

        // Santa Rosa (14 municipios) [citation:3][citation:5]
        $santaRosa = $departamentos['Santa Rosa'];
        $municipiosSantaRosa = [
            'Barberena', 'Casillas', 'Chiquimulilla', 'Cuilapa', 'Guazacapán',
            'Nueva Santa Rosa', 'Oratorio', 'Pueblo Nuevo Viñas', 'San Juan Tecuaco',
            'San Rafael Las Flores', 'Santa Cruz Naranjo', 'Santa María Ixhuatán',
            'Santa Rosa de Lima', 'Taxisco'
        ];

        // Sololá (19 municipios) [citation:3][citation:5]
        $solola = $departamentos['Sololá'];
        $municipiosSolola = [
            'Concepción', 'Nahualá', 'Panajachel', 'San Andrés Semetabaj',
            'San Antonio Palopó', 'San José Chacayá', 'San Juan La Laguna',
            'San Lucas Tolimán', 'San Marcos La Laguna', 'San Pablo La Laguna',
            'San Pedro La Laguna', 'Santa Catarina Ixtahuacán', 'Santa Catarina Palopó',
            'Santa Clara La Laguna', 'Santa Cruz La Laguna', 'Santa Lucía Utatlán',
            'Santa María Visitación', 'Santiago Atitlán', 'Sololá'
        ];

        // Suchitepéquez (21 municipios) [citation:3][citation:5]
        $suchitepequez = $departamentos['Suchitepéquez'];
        $municipiosSuchitepequez = [
            'Chicacao', 'Cuyotenango', 'Mazatenango', 'Patulul', 'Pueblo Nuevo',
            'Río Bravo', 'Samayac', 'San Antonio Suchitepéquez', 'San Bernardino',
            'San Francisco Zapotitlán', 'San Gabriel', 'San José El Ídolo',
            'San Juan Bautista', 'San Lorenzo', 'San Miguel Panán', 'San Pablo Jocopilas',
            'Santa Bárbara', 'Santo Domingo Suchitepéquez', 'Santo Tomás La Unión',
            'Zunilito', 'San José La Máquina'
        ];

        // Totonicapán (8 municipios) [citation:3][citation:5]
        $totonicapan = $departamentos['Totonicapán'];
        $municipiosTotonicapan = [
            'Momostenango', 'San Andrés Xecul', 'San Bartolo', 'San Cristóbal Totonicapán',
            'San Francisco El Alto', 'Santa Lucía La Reforma', 'Santa María Chiquimula',
            'Totonicapán'
        ];

        // Zacapa (11 municipios) [citation:3][citation:5]
        $zacapa = $departamentos['Zacapa'];
        $municipiosZacapa = [
            'Cabañas', 'Estanzuela', 'Gualán', 'Huite', 'La Unión', 'Río Hondo',
            'San Diego', 'San Jorge', 'Teculután', 'Usumatlán', 'Zacapa'
        ];

        // Insertar todos los municipios
        $this->insertMunicipios($altaVerapaz, $municipiosAltaVerapaz);
        $this->insertMunicipios($bajaVerapaz, $municipiosBajaVerapaz);
        $this->insertMunicipios($chimaltenango, $municipiosChimaltenango);
        $this->insertMunicipios($chiquimula, $municipiosChiquimula);
        $this->insertMunicipios($elProgreso, $municipiosElProgreso);
        $this->insertMunicipios($escuintla, $municipiosEscuintla);
        $this->insertMunicipios($guatemala, $municipiosGuatemala);
        $this->insertMunicipios($huehuetenango, $municipiosHuehuetenango);
        $this->insertMunicipios($izabal, $municipiosIzabal);
        $this->insertMunicipios($jalapa, $municipiosJalapa);
        $this->insertMunicipios($jutiapa, $municipiosJutiapa);
        $this->insertMunicipios($peten, $municipiosPeten);
        $this->insertMunicipios($quetzaltenango, $municipiosQuetzaltenango);
        $this->insertMunicipios($quiche, $municipiosQuiche);
        $this->insertMunicipios($retalhuleu, $municipiosRetalhuleu);
        $this->insertMunicipios($sacatepequez, $municipiosSacatepequez);
        $this->insertMunicipios($sanMarcos, $municipiosSanMarcos);
        $this->insertMunicipios($santaRosa, $municipiosSantaRosa);
        $this->insertMunicipios($solola, $municipiosSolola);
        $this->insertMunicipios($suchitepequez, $municipiosSuchitepequez);
        $this->insertMunicipios($totonicapan, $municipiosTotonicapan);
        $this->insertMunicipios($zacapa, $municipiosZacapa);

        $this->command->info('✅ Se han creado los 340 municipios de Guatemala');
    }

    private function insertMunicipios($departamentoId, array $municipios)
    {
        foreach ($municipios as $nombre) {
            Municipio::create([
                'nombre' => $nombre,
                'departamento_id' => $departamentoId,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        }
    }
}
