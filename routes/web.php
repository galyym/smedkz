<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('socket.socket');
})->name('home');

Route::get('broadcast', function (){
    broadcast(new \App\Events\NewMessage());
});

Route::post('init-event', function (Request $request){
   $data = [
                'topic_id' => 'keremet',
                'data' => [
                            'command' => $request->get('command'),
                            'description' => $request->get('description')
                            ],
           ];
            \App\Classes\Socket\Pusher::sentDataToServer($data);
            return redirect()->route('home');
})->name('socket');

Route::get("check", function (){
    $con = DB::connection();
    $query = DB::table("elib_log")->insert([
        "id" => 1,
        "iin" => 256585,
        "datatime_reg" => date("Y-m-d H:i:s"),
        "action" => "jkandlkf",
        "elib_positions" => "skfjbjsfbiubirguybgjddbfjdjdjfb",
        "device_type_id" => 2,
        "device_id" =>  54,
        "identificator_id" => 54
    ]);
    dd($con, "=>", $query);
});

Route::get("redis", function (){
    $redis = Redis::connection();
    if ($redis->ping()){
        dd("ping.......");
    }else{
        dd("no ping...");
    }
});
