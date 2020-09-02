<?php

namespace App\Http\Controllers\Api;

use App\Appointment;
use App\Http\Controllers\Controller;
use App\Http\Resources\AppointmentResource;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LawyerController extends Controller
{   
    public function index(Request $request)
    {
        if (!empty($request->search) && $request->search != 'null') {
            $appointments = $request->user()->lawyerAppointments()->whereHas('user', function ($query) use ($request) {
                return $query->where('name', 'like', '%' . ($request->search . '%'));
            })->orderBy($request->order, $request->sort);;
        } else {
            $appointments = $request->user()->lawyerAppointments()
                ->orderBy($request->order, $request->sort);
        }

        if ($request->status != 'all') {
            $appointments->where('status', '=', $request->status);
        }

        return AppointmentResource::collection(
            $appointments->paginate(10)
        );
    }

    public function update(Request $request)
    {
        if (!in_array($request->status, Appointment::STATUSES)) {
            return response(['Error' => 'Wrong status'], 400);
        }

        $appointment = $request->user()->lawyerAppointments()->findOrFail($request->id);
        $appointment->update(['status' => $request->status]);

        return response([$appointment], 200);
    }
}
