<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\ScheduleDay;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index(Request $request) {
        $doctor = Doctor::select('id', 'department_id', 'job', "$request->weekday")
        ->where('id', '>', '0');
        if (isset($request->weekday)) {
            $doctor = $doctor->whereNotNull($request->weekday);
        }
        return response()->json(["doctors"=>$doctor->paginate(15)]);
    }

    public function show($id) {
        $doctor = Doctor::with('services')->findOrFail($id);
        $weekdays = ['monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday'];
        foreach ($weekdays as $weekday) {
            if ($weekday != null) {
                $scheduleDay = ScheduleDay::find($doctor[$weekday]);
                $doctor[$weekday] = $scheduleDay;
            }
        }
        return response()->json($doctor);
    }

    public function store(Request $request) {
        $request->validate([
            "user_id"=>"required|exists:users,id|unique:doctors,user_id",
            "department_id"=>"required|exists:departments,id",
            "job"=>"required|string|max:255"
        ]);

        $doctor = Doctor::create([
            "user_id"=>$request->user_id,
            "department_id"=>$request->department_id,
            "job"=>$request->job
        ]);

        return response()->json($doctor, 201);
    }

    public function destroy($id) {
        $doctor = Doctor::find($id);

        if (!isset($doctor->id)) {
            return response()->json(["message"=>"Doctor not found"], 404);
        }
        
        $doctor->delete();
        
        return response(status: 204);
    }

    public function update($id, Request $request) {
        $request->validate([
            "job"=>"string|max:255"
        ]);

        $doctor = Doctor::findOrFail($id);

        $weekDays = ["monday", "tuesday", "wednesday", 
        "thursday", "friday", "saturday", "sunday"];
        
        foreach ($weekDays as $weekDay) {
            if ($request->has($weekDay)) {
                if ($request[$weekDay] == null) {
                    $doctor[$weekDay] = null;
                }
                else {
                    $w = $request[$weekDay];
                    $scheduleDay = 
                    ScheduleDay::where('start', $w['start'])->where('end', $w['end'])
                        ->where('break_from', $w['break_from'])->where('break_to', $w['break_to'])
                        ->first();
                    if (!isset($scheduleDay->id)) {
                        $scheduleDay = ScheduleDay::create([
                            "start"=>$w['start'],
                            "end"=>$w['end'],
                            "break_from"=>$w['break_from'],
                            "break_to"=>$w['break_to']
                        ]);
                    }

                    $doctor[$weekDay] = $scheduleDay->id;
                }
            }
        }

        if (isset($request->job)) {
            $doctor->job = $request->job;
        }

        $doctor->save();

        return response()->json(status: 204);

    }

}