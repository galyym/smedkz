<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redis;
use App\Http\Controllers\LocalController;
use App\Http\Controllers\CloudController;
use App\Http\Controllers\User\AddUserCloudController;

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

Route::group(['prefix' => 'send', 'middleware' => 'check'], function (){
    Route::post("sendsql", [LocalController::class, 'index']);
    Route::post("cloud", [CloudController::class, "index"]);
    Route::post("add/user", [AddUserCloudController::class, "addUserCloud"]);
    Route::post("add/admin", [AddUserCloudController::class, "addAdmin"]);
});


// Route который добавить в redis логин и пароль DB. У каждого школы есть свой DB и соответственно доступ к этому DB. И мы эти доступы храним
// в redis-е.
Route::post("red", function (Request $request){
    $redis = Redis::connection();
    if ($request->key == env("KEY_TOBIRAMA")) {
        $a = $redis->keys("*");
        dd($a);
    }else{
        dd(":)");
    }
//    }
//    $license = $request->get("license");
//    $redis_data = [
//        "db" => "smartdb_".$request->get("license"),
//        "user" => $request->get("db_user"),
//        "password" => $request->get("db_password"),
//        "host" => $request->get("db_host")
//    ];
//    $redis->set($license, json_encode($redis_data));
//
//    $db_names = [];
//    $a = $redis->keys("*");
//    foreach ($a as $name){
//        $db_names[] = $redis->get(substr($name, 17, 28));;
//    }
//
//    $key = $redis->get($license);
//    dd($a, $db_names, $key);
});





















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
        "id" => 2,
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

Route::get("redis", function (Request $request){
    $redis = Redis::connection();
//    $list = [
//        'db' => $request->get('db'),//'smartdb_KZAS12010001',
//        'user' => $request->get('user'),//'groot',
//        'password' => $request->get('password')//'](XJ=@tE!prR'
//    ];
//    $redis->set($request->get('key'), json_encode($list));
//    dd($redis->keys("*"));
    dd($redis->get($request->get('key')));
});
