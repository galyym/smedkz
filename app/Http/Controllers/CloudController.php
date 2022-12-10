<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CloudController extends Controller
{
    public function index(Request $request){

        $update_date = $request->get("date");
        $data = [];

        $connect = DB::connection('mysql');
        foreach ($update_date as $item) {
            $d = $connect->table($item["table"])->select("*")->where("updated_at", ">", $item["updated_at"])->get();
            if ($d->toArray()){
                $data[] = [
                    "table" => $item["table"],
                    "data" => $d
                ];
            }
        }

        return response([
            'status' => 'success',
            'message' => 'We check db successfully',
            'data' => $data
        ]);
    }
}
