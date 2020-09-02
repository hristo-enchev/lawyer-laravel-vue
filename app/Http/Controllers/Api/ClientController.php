<?php

namespace App\Http\Controllers\Api;

use App\Appointment;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Resources\AppointmentResource;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        if (!empty($request->search) && $request->search != 'null') {
            $appointments = $request->user()->appointments()->whereHas('lawyer', function ($query) use ($request) {
                return $query->where('name', 'like', ('%' . $request->search . '%'));
            })->orderBy($request->order, $request->sort);
        } else {
            $appointments = $request->user()->appointments()
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

        $appointment = $request->user()->appointments()->findOrFail($request->id);
        $appointment->update(['status' => $request->status]);

        return response([], 200);
    }

    public function delete(Request $request)
    {
        $request->user()->appointments()->findOrFail($request->id)->delete();

        return response([], 200);
    }

    public function reschedule(Request $request)
    {
        $request->validate([
            'date' => 'required|date_format:Y-m-d|after_or_equal:' . date('Y-m-d'),
        ]);

        $dateFrom = date('Y-m-d ' . ($request->from . ':00'), strtotime($request->date));
        $dateTo = date('Y-m-d ' . ($request->to . ':00'), strtotime($request->date));

        $appointment = $request->user()->appointments()->findOrFail($request->id);

        $appointment->update([
            'from_time' => $dateFrom,
            'to_time' => $dateTo,
            'status' => 'pending',
            'details' => $request->details
        ]);

        return response([], 200);
    }
}
