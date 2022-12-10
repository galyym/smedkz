<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class CheckSchoolLicense extends Controller
{

    public function index(Request $request)
    {
        $query = DB::table("connection")->select('owner')->where("license", "=", $request->license)->first();
        if ($query != null || !empty($query)){
            return $query->owner;
        }else{
            return null;
        }
    }
}
