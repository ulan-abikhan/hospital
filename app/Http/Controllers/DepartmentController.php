<?php

namespace App\Http\Controllers;

use App\Http\Requests\DepartmentRequest;
use App\Models\Department;
use Illuminate\Http\Request;

class DepartmentController extends Controller
{

    public function store(DepartmentRequest $request) {
        $department = Department::create([
            "hospital_id"=>$request->hospital_id,
            "name"=>$request->name
        ]);

        return response()->json($department, 201, 
        headers: ["X-Dep-Id"=>$department->id]);
    }

    public function store2(Request $request) {
        $request->validate([
            "hospital_id"=>'required|exists:hospitals,id',
            "name"=>'required|max:255|string'
        ]);

        $department = Department::create([
            "hospital_id"=>$request->hospital_id,
            "name"=>$request->name
        ]);

        return response()->json($department, 201, 
        headers: ["X-Dep-Id"=>$department->id]);
    }

    public function index() {
        $departments = Department::all();
        return response()->json(["departments"=>$departments]);
    }

    public function update($id, Request $request) {
        $department = Department::find($id);

        $request->validate([
            "name"=>'required|max:255|string'
        ]);

        if (!isset($department->id)) {
            return response()->json(["message"=>"Deparment not found"], 404);
        }

        $department->name = $request->name;

        $department->save();

        return response(status: 204);
    }

    public function destroy($id) {
        $department = Department::find($id);

        if (!isset($department->id)) {
            return response()->json(["message"=>"Deparment not found"], 404);
        }

        $department->delete();

        return response(status: 204);
    }

}
