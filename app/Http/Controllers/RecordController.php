<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\Record;
use App\Models\ScheduleDay;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RecordController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $user = $request->user();

        if ($user->role != 'client') {
            return response()->json(["message"=>"Not permitted"], 403);
        }

        $request->validate([
            'doctor_id'=>'required|exists:doctors,id',
            'service_id'=>'required|exists:services,id',
            'date'=>'required|date',
            'start'=>'required|date_format:H:i:s',
            'end'=>'required|date_format:H:i:s'
        ]);

        $doctor = Doctor::find($request->doctor_id);

        $services = $doctor->services;
        $rightService = null;

        foreach ($services as $service) {
            if ($service->id == $request->service_id) {
                $rightService = $service;
                break;
            }
        }

        if ($rightService == null) {
            return response()->json(["message"=>"Doctor doesn't do this service."], 400);
        }

        $date = Carbon::parse($request->date);

        $weekday = strtolower($date->dayName);

        if ($doctor[$weekday] == null) {
            return response()->json(["message"=>"Rest day for doctor"], 400);
        }

        $schedule = ScheduleDay::find($doctor[$weekday]);

        // до начала рабочего дня не принимать
        if ($schedule->start > $request->start || $schedule->start > $request->end) {
            return response()->json(["message"=>"Not valid time"], 400);
        }

        // после рабочего дня не принимать
        if ($schedule->end < $request->start || $schedule->end < $request->end) {
            return response()->json(["message"=>"Not valid time"], 400);
        }

        // ВРЕМЯ Перерыва
        // Время не должно находится в перемене
        if ($request->start >= $schedule->break_from && 
        $request->end <= $schedule->break_to) {
            return response()->json(["message"=>"Break time"], 400);
        }

        // Время начала записи совпадает, не совпадает время конца 
        if ($request->start < $schedule->break_from && 
        ($request->end <= $schedule->break_to && $request->end > $schedule->break_from)) {
            return response()->json(["message"=>"Break time"], 400);
        }

        // Время конца записи совпадает, не совпадает время начала 
        if ($request->end > $schedule->break_to && (
            $request->start >= $schedule->break_from && $request->start 
            < $schedule->break_to
        )) {
            return response()->json(["message"=>"Break time"], 400);
        }
        
        // Время начала и конца совпадает, но внутри находится время перерыва
        if ($request->start < $schedule->break_from && $request->end > $schedule->break_to) {
            return response()->json(["message"=>"Break time"], 400);
        }

        // $record = Record::where();
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
