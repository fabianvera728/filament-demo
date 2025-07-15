<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Holiday;

class HolidaySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $currentYear = now()->year;
        $nextYear = $currentYear + 1;

        $holidays = [
            // Días festivos fijos
            [
                'name' => 'Año Nuevo',
                'date' => "{$currentYear}-01-01",
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => true,
                'description' => 'Celebración del Año Nuevo',
            ],
            [
                'name' => 'Día del Trabajo',
                'date' => "{$currentYear}-05-01",
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => true,
                'description' => 'Día Internacional del Trabajador',
            ],
            [
                'name' => 'Día de la Independencia',
                'date' => "{$currentYear}-07-20",
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => true,
                'description' => 'Independencia de Colombia',
            ],
            [
                'name' => 'Batalla de Boyacá',
                'date' => "{$currentYear}-08-07",
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => true,
                'description' => 'Conmemoración de la Batalla de Boyacá',
            ],
            [
                'name' => 'Inmaculada Concepción',
                'date' => "{$currentYear}-12-08",
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Día de la Inmaculada Concepción',
            ],
            [
                'name' => 'Navidad',
                'date' => "{$currentYear}-12-25",
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => true,
                'description' => 'Celebración de la Navidad',
            ],

            // Días festivos que se trasladan al lunes (Ley Emiliani)
            [
                'name' => 'Día de los Reyes Magos',
                'date' => $this->getNextMonday("{$currentYear}-01-06"),
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Epifanía del Señor',
            ],
            [
                'name' => 'Día de San José',
                'date' => $this->getNextMonday("{$currentYear}-03-19"),
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Día de San José',
            ],
            [
                'name' => 'Ascensión del Señor',
                'date' => $this->getEasterDate($currentYear, 39), // 39 días después de Pascua
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Ascensión del Señor',
            ],
            [
                'name' => 'Corpus Christi',
                'date' => $this->getEasterDate($currentYear, 60), // 60 días después de Pascua
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Corpus Christi',
            ],
            [
                'name' => 'Sagrado Corazón',
                'date' => $this->getEasterDate($currentYear, 68), // 68 días después de Pascua
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Sagrado Corazón de Jesús',
            ],
            [
                'name' => 'San Pedro y San Pablo',
                'date' => $this->getNextMonday("{$currentYear}-06-29"),
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Día de San Pedro y San Pablo',
            ],
            [
                'name' => 'Asunción de la Virgen',
                'date' => $this->getNextMonday("{$currentYear}-08-15"),
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Asunción de la Virgen María',
            ],
            [
                'name' => 'Día de la Raza',
                'date' => $this->getNextMonday("{$currentYear}-10-12"),
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Día de la Raza',
            ],
            [
                'name' => 'Todos los Santos',
                'date' => $this->getNextMonday("{$currentYear}-11-01"),
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Día de Todos los Santos',
            ],
            [
                'name' => 'Independencia de Cartagena',
                'date' => $this->getNextMonday("{$currentYear}-11-11"),
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Independencia de Cartagena',
            ],

            // Fechas especiales para el negocio
            [
                'name' => 'Black Friday',
                'date' => $this->getBlackFriday($currentYear),
                'type' => 'commercial',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Día de descuentos especiales',
            ],
            [
                'name' => 'Día de San Valentín',
                'date' => "{$currentYear}-02-14",
                'type' => 'commercial',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Día del amor y la amistad',
            ],
            [
                'name' => 'Día de la Madre',
                'date' => $this->getMothersDay($currentYear),
                'type' => 'commercial',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Día de las madres en Colombia',
            ],
            [
                'name' => 'Día del Padre',
                'date' => $this->getFathersDay($currentYear),
                'type' => 'commercial',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Día de los padres en Colombia',
            ],
            [
                'name' => 'Halloween',
                'date' => "{$currentYear}-10-31",
                'type' => 'commercial',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Noche de Halloween',
            ],
        ];

        // Agregar días festivos del próximo año también
        $nextYearHolidays = [
            [
                'name' => 'Año Nuevo',
                'date' => "{$nextYear}-01-01",
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => true,
                'description' => 'Celebración del Año Nuevo',
            ],
            [
                'name' => 'Día de los Reyes Magos',
                'date' => $this->getNextMonday("{$nextYear}-01-06"),
                'type' => 'national',
                'is_recurring' => true,
                'affects_delivery' => false,
                'description' => 'Epifanía del Señor',
            ],
        ];

        $holidays = array_merge($holidays, $nextYearHolidays);

        foreach ($holidays as $holiday) {
            Holiday::create($holiday);
        }
    }

    private function getNextMonday($date): string
    {
        $dateObj = \Carbon\Carbon::parse($date);
        
        // Si es lunes, mantener la fecha
        if ($dateObj->dayOfWeek === 1) {
            return $dateObj->format('Y-m-d');
        }
        
        // Si no es lunes, mover al siguiente lunes
        return $dateObj->next(\Carbon\Carbon::MONDAY)->format('Y-m-d');
    }

    private function getEasterDate($year, $daysAfter = 0): string
    {
        // Algoritmo para calcular la fecha de Pascua
        $a = $year % 19;
        $b = intval($year / 100);
        $c = $year % 100;
        $d = intval($b / 4);
        $e = $b % 4;
        $f = intval(($b + 8) / 25);
        $g = intval(($b - $f + 1) / 3);
        $h = (19 * $a + $b - $d - $g + 15) % 30;
        $i = intval($c / 4);
        $k = $c % 4;
        $l = (32 + 2 * $e + 2 * $i - $h - $k) % 7;
        $m = intval(($a + 11 * $h + 22 * $l) / 451);
        $n = intval(($h + $l - 7 * $m + 114) / 31);
        $p = ($h + $l - 7 * $m + 114) % 31;
        
        $easter = \Carbon\Carbon::create($year, $n, $p + 1);
        
        if ($daysAfter > 0) {
            $easter = $easter->addDays($daysAfter);
            // Mover al siguiente lunes si no es lunes
            if ($easter->dayOfWeek !== 1) {
                $easter = $easter->next(\Carbon\Carbon::MONDAY);
            }
        }
        
        return $easter->format('Y-m-d');
    }

    private function getBlackFriday($year): string
    {
        // Black Friday es el día después del cuarto jueves de noviembre
        $thanksgiving = \Carbon\Carbon::create($year, 11, 1)->nthOfMonth(4, \Carbon\Carbon::THURSDAY);
        return $thanksgiving->addDay()->format('Y-m-d');
    }

    private function getMothersDay($year): string
    {
        // En Colombia es el segundo domingo de mayo
        return \Carbon\Carbon::create($year, 5, 1)->nthOfMonth(2, \Carbon\Carbon::SUNDAY)->format('Y-m-d');
    }

    private function getFathersDay($year): string
    {
        // En Colombia es el tercer domingo de junio
        return \Carbon\Carbon::create($year, 6, 1)->nthOfMonth(3, \Carbon\Carbon::SUNDAY)->format('Y-m-d');
    }
}
