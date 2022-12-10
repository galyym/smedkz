<?php

namespace App\Http\Controllers\SSH;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SSHController extends Controller
{
    public function setSSH(Request $request){
        $query = DB::table("connection")->where("license", "=", $request->license)->update([
           "ssh_".$request->git => $request->ssh
        ]);

        if ($query){
            return response("ssh successfully added");
        }else{
            return response("ssh has not been added");
        }
    }
}
