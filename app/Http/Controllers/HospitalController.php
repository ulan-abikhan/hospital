<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    
    public function index(Request $request) {
        return Hospital::all();
    }

    public function store(Request $request) {
        $request->validate([
            "name"=>"required|string|max:255",
            "address"=>"required|string|max:255",
            "bin"=>"required|string|max:255"
        ]);

        $hospital = Hospital::create([
            "name"=>$request->name,
            "address"=>$request->address,
            "bin"=>$request->bin
        ]);

        return response()->json($hospital, 201);

    }

    public function update($id, Request $request) {
        $hospital = Hospital::find($id);
        if (!isset($hospital->id)) {
            return response()->json(["message"=>"Hospital with id=$id not found."], 404);
        }

        if (isset($request['name'])) {
            $hospital->name = $request['name'];
        }

        if (isset($request['bin'])) {
            $hospital->bin = $request['bin'];
        }

        if (isset($request['address'])) {
            $hospital->address = $request['address'];
        }

        $hospital->save();

        return response(status: 204);

    }

    public function destroy($id) {
        $hospital = Hospital::find($id);
        if (!isset($hospital->id)) {
            return response()->json(["message"=>"Hospital with id=$id not found."], 404);
        }

        $hospital->delete();

        return response(status: 204);
    }
    
}
