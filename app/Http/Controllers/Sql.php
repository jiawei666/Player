<?php
namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class Sql extends Controller{
    public function index(){

        $info = \DB::table('student')->get();
        dd($info);
    }
}