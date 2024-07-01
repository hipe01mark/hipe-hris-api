<?php

namespace App\Console\Commands;

use App\Libraries\GoogleCalendarLib;
use App\Models\Holiday;
use Illuminate\Console\Command;

class ImportHolidays extends Command
{
    CONST JAPANESE_HOLIDAY = [
        'code' => 'japanese',
        'country_name' => 'Japan'
    ];
    CONST PHILIPPINE_HOLIDAY = [
        'code' => 'philippines',
        'country_name' => 'Philippines'
    ];

    CONST COLOR_DEFAULT = '#000000';
    CONST COLOR_PHILIPPINES = '#0038A8';
    CONST COLOR_JAPAN = '#BC002D';  

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'import:holidays';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import holidays of PH and JA using google calendar';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $calendar = new GoogleCalendarLib();
        $phHolidays = $calendar->getHolidays(self::PHILIPPINE_HOLIDAY['code']);
        $jaHolidays = $calendar->getHolidays(self::JAPANESE_HOLIDAY['code']);

        $this->saveHolidays($phHolidays, self::PHILIPPINE_HOLIDAY);
        $this->saveHolidays($jaHolidays, self::JAPANESE_HOLIDAY);

        $this->info('PH and JP holiday imported successfully!');
    }

    /**
     * Save holidays
     */
    public function saveHolidays(array $holidays, array $country): void
    {
        $countryCode = $country['code'];
        $countryName = $country['country_name'];
        
        foreach ($holidays as $holiday) {
            $requests = [
                'country' => $countryName,
                'title' => $holiday['summary'],
                'description' => $holiday['description'],
                'hex_code' => $this->generateHexCode($countryCode),
                'start_date' => $holiday['start'],
                'end_date' => $holiday['end'],
            ];
            
            Holiday::updateOrCreate($requests, $requests);
        }
    }

    /**
     * Generate hex code based on country code
     */
    private function generateHexCode(string $countryCode): string
    {
        $flagColors = [
            self::PHILIPPINE_HOLIDAY['code'] => self::COLOR_PHILIPPINES,
            self::JAPANESE_HOLIDAY['code'] => self::COLOR_JAPAN
        ];

        return $flagColors[$countryCode] ?? self::COLOR_DEFAULT;
    }
}
