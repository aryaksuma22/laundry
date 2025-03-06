<?php

namespace App\Http\Controllers;

use App\Models\Medicine;
use Illuminate\Http\Request;

class MedicineController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $medicines = Medicine::all();
            return response()->json($medicines);
        }
        return view('medicines.index');    
    }  
}
