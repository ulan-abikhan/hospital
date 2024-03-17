<?php

namespace App\Http\Controllers;

use App\Models\Hospital;
use Illuminate\Http\Request;

class HospitalController extends Controller
{
    
    public function index(Request $request) {
        return Hospital::all();
    }

}
