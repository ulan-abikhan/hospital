<?php

namespace App\Http\Controllers;

use App\Models\Doctor;
use App\Models\ScheduleDay;
use Illuminate\Http\Request;

class DoctorController extends Controller
{
    public function index() {
        return response()->json(["doctors"=>Doctor::all()]);
    }

    public function show($id) {
        $doctor = Doctor::findOrFail($id);
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
                    $scheduleDay = ScheduleDay::create([
                        "start"=>$w['start'],
                        "end"=>$w['end'],
                        "break_from"=>$w['break_from'],
                        "break_to"=>$w['break_to']
                    ]);

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