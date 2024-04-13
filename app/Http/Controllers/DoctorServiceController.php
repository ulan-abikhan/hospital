<?php

namespace App\Http\Controllers;

use App\Models\DoctorServiceLink;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DoctorServiceController extends Controller
{
    
    public function store($service_id, Request $request) {
        $request->validate([
            "doctor_id"=>"required|exists:doctors,id"
        ]);

        // $doctorService = DoctorServiceLink::create([
        //     "service_id"=>$service_id,
        //     "doctor_id"=>$request['doctor_id']
        // ]);
        $doctorService = DoctorServiceLink::
            where('doctor_id', $request['doctor_id'])
            ->where('service_id', $service_id)->first();
        if (!isset($doctorService->doctor_id)) {
            $doctorService = new DoctorServiceLink();

            $doctorService->service_id = $service_id;
            $doctorService->doctor_id = $request['doctor_id'];

            $doctorService->save();
        }
        else {
            return response()->json(["message"=>"Already exists."], 400);
        }

        // $ds = DB::table('doctor_service_links')->insert([
        //     "service_id"=>$service_id,
        //     "doctor_id"=>$request['doctor_id']
        // ]);

        return response(status: 201);

    }

    public function destroy($service_id, Request $request) {
        $request->validate([
            "doctor_id"=>"required|exists:doctors,id"
        ]);

        $doctor_id = $request->query("doctor_id");

        DoctorServiceLink::where('doctor_id', $doctor_id)
            ->where('service_id', $service_id)->delete();
        
        return response(status: 204);
    }

}