<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Services\DataBaseService;

class AddUserCloudController extends Controller
{
    protected $service;
    public function __construct(DataBaseService $service){
        $this->service = $service;
    }

    public function addUserCloud(Request $request)
    {
        //собираюсь добавить регистрированных карточек на сервер
        $this->service->connectDB('KUKA');

        $query = DB::connection("KUKA")
            ->table("cards_ready")
            ->select("*")
            ->where("mektep_id", "=", $request->mektep_id)
            ->where('status',  'like','student')
            ->get();

        dd($query);


        foreach ($query as $q){
            $hex_reverse_split = [];
            $hex_real = [];
            $turniket_hex = "";
            $turniket_desimal = "";

            if (!empty($request->length) && $request->length == 6){
                $hex_reverse_split = str_split(substr($q->nfc, 0, -4), 2);
            }else{
                $hex_reverse_split = str_split(substr($q->nfc, 0, -2), 2);
            }

            $hex_real = array_reverse($hex_reverse_split);
            $turniket_hex = implode($hex_real);
            $turniket_desimal = hexdec(implode($hex_real));

            $data[] = [
                "iin" => $q->iin,
                "full_name" => $q->full_name,
                "rfid" => $q->rfid,
                "nfc" => $q->nfc,
                "qr_code" => md5($q->iin),
                "card_number" => $q->card_number,
                "is_food_free" => 1,
                "is_active" => 1,
                "full_hex" => $q->nfc,
                "full_decimal" => (string)hexdec($q->nfc),
                "real_hex" => $turniket_hex,
                "real_decimal" => (string)$turniket_desimal,
                "turniket_hex" => $turniket_hex,
                "turniket_decimal" => (string)$turniket_desimal
            ];

        }

        $insert = DB::connection("mysql")
            ->table("loc_user")
            ->updateOrInsert($data);

        dd($insert);
    }

    public function addAdmin(Request $request){
        Config::set('database.connections.kukadb', [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => env('KUKA_DB_HOST'),
            'port' => env('KUKA_DB_PORT'),
            'database' => 'cards_log',
            'username' => env('KUKA_DB_USER'),
            'password' => "",
            'unix_socket' => "",
            'charset' => 'utf8mb4',
            'collation' => 'utf8mb4_unicode_ci',
            'prefix' => '',
            'prefix_indexes' => true,
            'strict' => true,
            'engine' => null
        ]);

        $query = DB::connection("kukadb")
            ->table("cards_ready")
            ->select("*")
            ->where("status", '=','admin')
            ->where("mektep_id", "=", $request->mektep_id)
            ->get();

        foreach ($query as $data){
            if (!empty($request->length) && $request->length == 6){
                $hex_reverse_split_real = str_split(substr($data->nfc, 0, -2), 2);
                $hex_reverse_split_turkniket = str_split(substr($data->nfc, 0, -4), 2);
            }else{
                $hex_reverse_split_real = str_split(substr($data->nfc, 0, -2), 2);
                $hex_reverse_split_turkniket = str_split(substr($data->nfc, 0, -2), 2);
            }
            $real_nfc = array_reverse($hex_reverse_split_real);
            $hex_real = implode($real_nfc);
            $decimal_real = hexdec(implode($real_nfc));

            $hex_turniket = array_reverse($hex_reverse_split_turkniket);
            $turniket_hex = implode($hex_turniket);
            $turniket_desimal = hexdec(implode($hex_turniket));

            $query_data = [
                "iin" => $data->iin,
                "full_name" => $data->full_name,
                "rfid" => $data->rfid,
                "nfc" => $data->nfc,
                "qr_code" => md5($data->iin),
                "card_number" => $data->card_number,
                "is_food_free" => 1,
                "is_active" => 1,
                "full_hex" => $data->nfc,
                "full_decimal" => (string)hexdec($data->nfc),
                "real_hex" => $hex_real,
                "real_decimal" => (string)$decimal_real,
                "turniket_hex" => $turniket_hex,
                "turniket_decimal" => (string)$turniket_desimal
            ];

        }
        DB::connection("mysql")
            ->table("loc_user")
            ->updateOrInsert($query_data);

        return 1;
    }

//    public function addPersonal(Request $request){
//
//    }
}
