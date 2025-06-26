<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\RegistroSeguridad;
use App\Models\User;
use Carbon\Carbon;

class RegistroSeguridadSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('🛡️ Creando registros de seguridad...');

        // 📊 ESTADÍSTICAS DE LO QUE SE VA A CREAR
        $totalRegistros = 0;

        // 🏃 RONDAS DE VIGILANCIA (20 registros)
        $this->command->info('🏃 Creando rondas de vigilancia...');
        
        // Rondas del último mes
        RegistroSeguridad::factory()
            ->ronda()
            ->deSeguridad()
            ->count(15)
            ->create();
        
        // Rondas de hoy
        RegistroSeguridad::factory()
            ->ronda()
            ->deSeguridad()
            ->deHoy()
            ->count(5)
            ->create();
            
        $totalRegistros += 20;

        // 🚨 INCIDENTES DE PERSONAL DE SEGURIDAD (15 registros)
        $this->command->info('🚨 Creando incidentes del personal de seguridad...');
        
        // Incidentes resueltos
        RegistroSeguridad::factory()
            ->incidente()
            ->deSeguridad()
            ->resuelto()
            ->count(10)
            ->create();
        
        // Incidentes pendientes (alta prioridad)
        RegistroSeguridad::factory()
            ->incidente()
            ->deSeguridad()
            ->altaPrioridad()
            ->pendiente()
            ->count(3)
            ->create();
        
        // Incidentes de hoy
        RegistroSeguridad::factory()
            ->incidente()
            ->deSeguridad()
            ->deHoy()
            ->count(2)
            ->create();
            
        $totalRegistros += 15;

        // 📋 REPORTES DE TURNO (12 registros)
        $this->command->info('📋 Creando reportes de turno...');
        
        // Reportes del último mes
        RegistroSeguridad::factory()
            ->reporte()
            ->deSeguridad()
            ->count(10)
            ->create();
        
        // Reportes de hoy
        RegistroSeguridad::factory()
            ->reporte()
            ->deSeguridad()
            ->deHoy()
            ->count(2)
            ->create();
            
        $totalRegistros += 12;

        // 🏠 REPORTES DE RESIDENTES (25 registros)
        $this->command->info('🏠 Creando reportes de residentes...');
        
        // Verificar que existan usuarios con rol de residente
        $residentes = User::whereHas('roles', function($q) {
            $q->where('name', 'Residente');
        })->count();
        
        if ($residentes > 0) {
            // Incidentes resueltos por residentes
            RegistroSeguridad::factory()
                ->deResidente()
                ->resuelto()
                ->count(15)
                ->create();
            
            // Incidentes pendientes por residentes
            RegistroSeguridad::factory()
                ->deResidente()
                ->pendiente()
                ->count(8)
                ->create();
            
            // Incidentes de hoy por residentes
            RegistroSeguridad::factory()
                ->deResidente()
                ->deHoy()
                ->count(2)
                ->create();
                
            $totalRegistros += 25;
        } else {
            $this->command->warn('⚠️ No se encontraron usuarios con rol "Residente". Saltando registros de residentes.');
        }

        // 🎯 REGISTROS ESPECÍFICOS PARA DEMOSTRACIÓN
        $this->command->info('🎯 Creando registros específicos de demostración...');
        
        // Buscar usuarios específicos para asignar registros
        $personalSeguridad = User::whereHas('roles', function($q) {
            $q->where('name', 'Personal de Seguridad');
        })->first();
        
        $residente = User::whereHas('roles', function($q) {
            $q->where('name', 'Residente');
        })->first();

        // Crear registros específicos si existen los usuarios
        if ($personalSeguridad) {
            // Ronda reciente del personal de seguridad
            RegistroSeguridad::factory()->create([
                'user_id' => $personalSeguridad->id,
                'tipo' => 'ronda',
                'origen' => 'seguridad',
                'fecha_hora' => Carbon::now()->subHours(2),
                'ubicacion' => 'Parqueadero nivel 1',
                'descripcion' => 'Ronda nocturna completada - Todo en orden',
                'prioridad' => 'baja',
                'estado' => 'resuelto',
                'observaciones' => 'Sin novedades durante la ronda'
            ]);

            // Incidente de alta prioridad pendiente
            RegistroSeguridad::factory()->create([
                'user_id' => $personalSeguridad->id,
                'tipo' => 'incidente',
                'origen' => 'seguridad',
                'fecha_hora' => Carbon::now()->subMinutes(30),
                'ubicacion' => 'Entrada principal',
                'descripcion' => 'Intento de ingreso de persona no autorizada',
                'prioridad' => 'alta',
                'estado' => 'pendiente',
                'observaciones' => 'Requiere seguimiento inmediato'
            ]);
            
            $totalRegistros += 2;
        }

        if ($residente) {
            // Reporte de residente pendiente
            RegistroSeguridad::factory()->create([
                'user_id' => $residente->id,
                'tipo' => 'incidente',
                'origen' => 'residente',
                'fecha_hora' => Carbon::now()->subHours(4),
                'ubicacion' => 'Piso 3 - Pasillo',
                'descripcion' => 'Ruido excesivo en unidad vecina después de las 22:00',
                'prioridad' => 'media',
                'estado' => 'pendiente',
                'observaciones' => 'Problema recurrente durante los fines de semana'
            ]);

            // Reporte de residente ya resuelto
            RegistroSeguridad::factory()->create([
                'user_id' => $residente->id,
                'tipo' => 'incidente',
                'origen' => 'residente',
                'fecha_hora' => Carbon::now()->subDays(2),
                'ubicacion' => 'Ascensor A',
                'descripcion' => 'Ascensor con fallas mecánicas',
                'prioridad' => 'media',
                'estado' => 'resuelto',
                'resuelto_por' => $personalSeguridad?->id,
                'fecha_resolucion' => Carbon::now()->subDay(),
                'observaciones' => 'Mantenimiento realizado exitosamente'
            ]);
            
            $totalRegistros += 2;
        }

        // 📈 REGISTROS DISTRIBUIDOS POR FECHAS
        $this->command->info('📅 Creando registros distribuidos en el tiempo...');
        
        // Registros de la última semana
        for ($i = 1; $i <= 7; $i++) {
            $fecha = Carbon::now()->subDays($i);
            
            // 1-2 rondas por día
            RegistroSeguridad::factory()
                ->ronda()
                ->deSeguridad()
                ->create([
                    'fecha_hora' => $fecha->copy()->setHour(rand(0, 23))->setMinute(rand(0, 59))
                ]);
            
            // Algunos incidentes aleatorios
            if (rand(1, 3) === 1) { // 33% de probabilidad
                RegistroSeguridad::factory()
                    ->incidente()
                    ->create([
                        'fecha_hora' => $fecha->copy()->setHour(rand(6, 22))->setMinute(rand(0, 59))
                    ]);
                $totalRegistros += 1;
            }
            
            $totalRegistros += 1;
        }

        // 📊 RESUMEN FINAL
        $this->command->info('');
        $this->command->info('✅ ¡Seeder completado exitosamente!');
        $this->command->info("📊 Total de registros creados: {$totalRegistros}");
        $this->command->info('');
        
        // Estadísticas por tipo
        $rondas = RegistroSeguridad::porTipo('ronda')->count();
        $incidentes = RegistroSeguridad::porTipo('incidente')->count();
        $reportes = RegistroSeguridad::porTipo('reporte')->count();
        
        $this->command->info("🏃 Rondas: {$rondas}");
        $this->command->info("🚨 Incidentes: {$incidentes}");
        $this->command->info("📋 Reportes: {$reportes}");
        $this->command->info('');
        
        // Estadísticas por origen
        $seguridad = RegistroSeguridad::deSeguridad()->count();
        $residentes = RegistroSeguridad::deResidentes()->count();
        
        $this->command->info("🚪 De Seguridad: {$seguridad}");
        $this->command->info("🏠 De Residentes: {$residentes}");
        $this->command->info('');
        
        // Estadísticas por estado
        $pendientes = RegistroSeguridad::pendientes()->count();
        $resueltos = RegistroSeguridad::resueltos()->count();
        
        $this->command->info("🔴 Pendientes: {$pendientes}");
        $this->command->info("🟢 Resueltos: {$resueltos}");
        $this->command->info('');
        
        $this->command->info('🎯 Los datos están listos para testing y demostración!');
    }
}