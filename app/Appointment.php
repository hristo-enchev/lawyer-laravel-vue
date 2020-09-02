<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use DatePeriod;
use DateTime;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use PhpParser\Node\Stmt\Foreach_;

class Appointment extends Model
{
    protected $fillable = ['from_time', 'to_time', 'status', 'details', 'lawyer_id'];

    // using constants to reduce complexity
    const START_WORKING_TIME = '9';
    const END_WORKING_TIME = '18';
    const APPOINTMENT_SLOT_HOURS = '1';
    const STATUSES = ['accepted', 'rejected', 'pending'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function lawyer()
    {
        return $this->belongsTo(User::class, 'lawyer_id');
    }

    public function scopeBetweenDates(Builder $query, $from, $to)
    {
        return $query->where('to_time', '>=', $from . ' 00:00:00')->where('from_time', '<=', $to . ' 23:59:59')->get();
    }

    public function scopeAvailableSlots(Builder $query, $from, $to)
    {
        $appointments = $query->betweenDates($from, $to)->where('status', '!=', 'rejected');
        $slots = $this->createSlots($from, $to, $appointments);

        return $slots;
    }

    /**
     * @desc Create slots based dates
     * @param string $from start date
     * @param string $to end date
     * @param collection $appointments end date
     * @return array
     */
    private function createSlots($from, $to, $appointments)
    {
        $interval = CarbonInterval::hour(Appointment::APPOINTMENT_SLOT_HOURS);
        $start =  Carbon::createFromFormat('Y-m-d', $from)->hour(Appointment::START_WORKING_TIME)->minute(0)->second(0);
        $end = Carbon::createFromFormat('Y-m-d', $to)->hour(Appointment::END_WORKING_TIME)->minute(0)->second(0);
        $slots = [];

        for ($days = 0; $days <= $start->diffInDays($end); $days++) {
            $startTime = (clone $start)->addDays($days);
            $endTime = (clone $startTime)->hour(Appointment::END_WORKING_TIME);

            foreach (new DatePeriod($startTime, $interval, $endTime) as $timeSlot) {
                $to = $timeSlot->copy()->add($interval);

                $slot = [
                    'slot_from' => $timeSlot->format('H:i'),
                    'slot_to' => $to->format('H:i'),
                    'available' => 'false'
                ];

                if ($this->checkSlotAvailability($timeSlot, $to, $appointments)) {
                    $slot['available'] = true;

                    $slots[$startTime->toDateString()][] = $slot;
                }

                // $slots[$startTime->toDateString()][] = $slot;
            }
        }

        return ['slots' => $slots];
    }

    /**
     * @param collection $appointments end date
     * @return bool
     */
    private function checkSlotAvailability($from, $to, $appointments)
    {
        foreach ($appointments as $appointment) {
            $eventStart = Carbon::instance(new DateTime($appointment['from_time']));
            $eventEnd = Carbon::instance(new DateTime($appointment['to_time']));

            if ($from->between($eventStart, $eventEnd) && $to->between($eventStart, $eventEnd)) {
                return false;
            }
        }

        return true;
    }
}
