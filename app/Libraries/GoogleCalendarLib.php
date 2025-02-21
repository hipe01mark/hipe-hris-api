<?php
namespace App\Libraries;

use Carbon\Carbon;
use Google\Client;
use Google\Service\Calendar;
use Illuminate\Support\Facades\Log;

class GoogleCalendarLib
{
    protected $client;

    CONST DEFAULT_ORDER_BY = 'startTime';
    CONST SINGLE_EVENTS = true;

    public function __construct()
    {
        $this->client = new Client();
        $this->client->setDeveloperKey(env('GOOGLE_API_KEY'));
        $this->client->setAuthConfig(config('services.google_calendar.credentials_path'));
        $this->client->setScopes([
            'https://www.googleapis.com/auth/calendar',
            'https://www.googleapis.com/auth/calendar.readonly'
        ]);
    }

    public function getHolidays(string $country): array
    {
        $service = new Calendar($this->client);
        $calendarId = "en.$country#holiday@group.v.calendar.google.com";

        $currentYear = Carbon::now()->year;
        $timeMin = Carbon::create($currentYear, 1, 1)->startOfDay()->toRfc3339String();
        $timeMax = Carbon::create($currentYear, 12, 31)->endOfDay()->toRfc3339String();

        try {
            $events = $service->events->listEvents($calendarId, [
                'timeMin' => $timeMin,
                'timeMax' => $timeMax,
                'singleEvents' => self::SINGLE_EVENTS,
                'orderBy' => self::DEFAULT_ORDER_BY,
            ]);

            $holidays = [];
            foreach ($events->getItems() as $event) {
                $holidays[] = [
                    'country' => $country,
                    'summary' => $event->getSummary(),
                    'description' => $event->getDescription(),
                    'start' => $event->getStart()->date,
                    'end' => $event->getEnd()->date,
                ];
            }

            return $holidays;
        } catch (\Google\Exception $e) {
            Log::error("Error fetching holidays for $country: " . $e->getMessage());
            return [];
        }
    }
}
