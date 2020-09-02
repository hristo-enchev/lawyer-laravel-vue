<?php

namespace App\Http\Controllers\Api;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request, $lawyerId)
    {
        $data = $request->validate([
            'from_time' => 'required|date_format:Y-m-d|after_or_equal:' . date('Y-m-d'),
            'to_time' => 'required:date_format:Y-m-d|after_or_equal:from_time'
        ]);

        $lawyer = User::findOrFail($lawyerId);

        return $lawyer->lawyerAppointments()->availableSlots($data['from_time'], $data['to_time']);
    }

    public function create(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d|after_or_equal:' . date('Y-m-d'),
        ]);

        $dateFrom = date('Y-m-d ' . ($request->from . ':00'), strtotime($request->date));
        $dateTo = date('Y-m-d ' . ($request->to . ':00'), strtotime($request->date));

        $appointment = $request->user()->appointments()->create([
            'from_time' => $dateFrom,
            'to_time' => $dateTo,
            'status' => 'pending',
            'lawyer_id' => $request->lawyerid,
            'details' => $request->details
        ]);

        if (!$appointment) {
            return response(['Error' => 'Record is not created!'], 400);
        }

        return response([]);
    }
}
