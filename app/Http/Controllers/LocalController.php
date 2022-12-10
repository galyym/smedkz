<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;

class LocalController extends Controller
{
    public function index(Request $request){
        $table_name = $request->get("table_name");
        $data = $request->get('data');

        // config/database. До этого момента в middleware->Http/Middleware/ConnectDatabase настроили mysql config

        $connect = DB::connection('mysql');
        DB::beginTransaction();
        try {
            if (count($data) > 100){   //если данные больше 100, то разделяем и властвуем, шучу, разделяем и делаем insert по 100 записей.
                $data_part = array_chunk($data, 100);

                foreach ($data_part as $item){
                    $connect->table($table_name)->insert($item);
                }
            }else {
                $connect->table($table_name)->insert($data);
            }
            DB::commit();

            $last_id = end($data);
            return response([
                "status" => "success",
                "message" => "Данные успешно добавлены",
                "id" => $last_id["id"]
            ]);
        }catch (\Exception $e){

            DB::rollBack();

            return response([
                "status" => "failed",
                "message" => [
                    "Данные не добавлены",
                    "line" => $e->getLine(),
                    "code" => $e->getCode(),
                    "message" => $e->getMessage()
                    ],
                "data" => []
            ]);
        }
    }
}
