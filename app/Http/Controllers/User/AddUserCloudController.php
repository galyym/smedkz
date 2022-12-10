<?php

namespace App\Http\Controllers\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class AddUserCloudController extends Controller
{
    public function addUserCloud(Request $request)
    {
        //собираюсь добавить регистрированных карточек на сервер
        Config::set('database.connections.kukadb', [
            'driver' => 'mysql',
            'url' => env('DATABASE_URL'),
            'host' => '188.0.152.123',
            'port' => '37827',
            'database' => 'cards_log',
            'username' => '192.168.31.20',
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
            ->where("mektep_id", "=", $request->mektep_id)
//            ->limit(10)
            ->get();

//        dd($query);



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

//        dd($data);

        $insert = DB::connection("mysql")
            ->table("loc_user")
            ->insert($data);

        dd($insert);
    }
}
