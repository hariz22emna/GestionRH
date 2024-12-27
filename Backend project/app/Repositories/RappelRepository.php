<?php

namespace App\Repositories;

use App\Models\Rappel;
use Carbon\Carbon;



class RappelRepository
{
    private const PERIODE_MAPPING =
        [
            'Quotidien' => "+1 day",
            'Hebdomadaire' => "+7 day",
            'Mensuel' => "+1 month",
            'Trimestriel' => "+3 month",
            'Semestriel' => "+6 month",
            'Annuel' => "+12 month"
        ];
    private const CALENDAR_ID = 'emnahariz2018@gmail.com';
    private function createSubRappels($rappelData, $originalRappelId)
    {
        $interval = self::PERIODE_MAPPING[$rappelData['periodicity']];
        $repeatCountMax = (int) $rappelData['repeatNumber'];
        $rappelDate = (new Carbon($rappelData['rappelDate']))->timezone(config('app.timezone'));
        $endDate = (new Carbon($rappelData['expireDate']))->timezone(config('app.timezone'));
        $added = 0;
        $nextDay = (new Carbon($rappelData['expireDate']))->timezone(config('app.timezone'));
        while ($added < $repeatCountMax && $nextDay <= $endDate) {
            $nextDay = $rappelDate->modify($interval);
            if ($nextDay < $endDate) {
                $newRappel = new Rappel();
                $newRappel->titre = $rappelData['titre'];
                $newRappel->resourceId = $rappelData['resourceId'];
                $newRappel->userId = $rappelData['userId'];
                $newRappel->repeatNumber = $rappelData['repeatNumber'];
                $newRappel->periodicity = $rappelData['periodicity'];
                $newRappel->typeAlerte = $rappelData['typeAlerte'];
                $newRappel->rappelDate = $nextDay;
                $newRappel->parentId = $originalRappelId;
                $newRappel->save();
                $added++;
                $eventId = $this->createGoogleCalendarEvent($rappelData['titre'], $nextDay);
                $newRappel->googleCalendarId = $eventId;
                $newRappel->save();

            }
        }
    }
    public function createRappel(array $requestData)
    {
        $rappel = Rappel::create($requestData);
        $rappelDate = (new Carbon($requestData['rappelDate']))->timezone(config('app.timezone'));

        $this->createSubRappels($requestData, $rappel->id);
        $eventId = $this->createGoogleCalendarEvent($requestData['titre'], $rappelDate);

        $rappel->googleCalendarId = $eventId;
        $rappel->save();
        return $rappel;
    }

    public function updateRappelInfo(array $request)
    {
        $rappel = Rappel::findOrFail($request['id']);
        $rappel->fill($request)->save();
        $this->deleteSubRappels($rappel->id);
        $this->createSubRappels($request, $rappel->id);
        return $rappel;
    }
    private function createGoogleCalendarEvent($title, $rappelDate)
    {
        $event = \Spatie\GoogleCalendar\Event::create(
            [
                'name' => $title,
                'startDateTime' => $rappelDate,
                'endDateTime' => $rappelDate,

            ],
            self::CALENDAR_ID
        );
        return $event->id;
    }
    private function deleteSubRappels($rappelId)
    {
        $rappels = Rappel::where('parentId', $rappelId)->get();
        foreach ($rappels as $rappel) {
            $this->deleteGoogleEvent($rappel->googleCalendarId);
            $rappel->delete();
        }
    }
    private function deleteGoogleEvent($eventId)
    {
        $event = \Spatie\GoogleCalendar\Event::find($eventId, self::CALENDAR_ID);
        $event->delete();
    }
    public function deleteRappel($id)
    {
        $rappel = Rappel::find($id);
        if ($rappel) {
            $rappel->isDeleted = 1;
            $rappel->save();
            $this->deleteGoogleEvent($rappel->googleCalendarId);
        }
        return $rappel;
    }
    public function getAllRappels()
    {
        $rappels = Rappel::with('resource')->where("isDeleted", "=", 0)->where("parentId", "=", null)->get();
        return $rappels;
    }

    public function getRappelById($id)
    {
        $rappel = Rappel::with('user')->findOrFail($id);
        return $rappel;
    }

}