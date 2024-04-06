<?php

namespace App\Http\Controllers;

use App\Models\Service;
use Illuminate\Http\Request;

class ServiceController extends Controller
{

    public function index($department_id) {
        if ($department_id == 0) {
            $services = Service::all();
        }
        else {
            $services = Service::where('department_id', $department_id)->get();
        }

        return response()->json(["services"=>$services]);
    }

    public function show($id) {
        $service = Service::findOrFail($id);

        return response()->json($service);
    }
    
    function store(Request $request) {
        $request->validate([
            'department_id'=>'required|exists:departments,id',
            'name'=>'required|string',
            'price'=>'required|integer',
            'duration'=>'required|date_format:H:i:s'
        ]);

        $service = Service::create([
            'department_id'=>$request->department_id,
            'name'=>$request->name,
            'price'=>$request->price,
            'duration'=>$request->duration
        ]);

        return response()->json(status: 201);

    }

    public function update($id, Request $request) {
        $request->validate([
            'name'=>'string',
            'price'=>'decimal:0,2',
            'duration'=>'date_format:H:i:s'
        ]);

        $service = Service::find($id);

        if (!isset($service->id)) {
            return response()->json(["message"=>"Not found"], 404);
        }

        if ($request->has('name')) {
            $service->name = $request->name;
        }

        if ($request->has('price')) {
            $service->price = $request->price;
        }

        if ($request->has('duration')) {
            $service->duration = $request->duration;
        }

        $service->save();

        return response(status: 204);

    }

    public function destroy($id) {
        $service = Service::find($id);

        if (!isset($service->id)) {
            return response()->json(["message"=>"Not found"], 404);
        }

        $service->delete();

        return response()->json(['message'=>'Deleted']);
    }

}