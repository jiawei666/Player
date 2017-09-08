<?php
/**
 * Created by PhpStorm.
 * User: 95708
 * Date: 2017/8/17
 * Time: 11:00
 */
namespace App\Http\Controllers;

use App\Test;

class TestController extends Controller{

    public function testFunction($id=null,$name=null){

        $id =  Test::getId($id,$name);
        return view('test/testFunction',['id'=>$id]);

    }

    public function activity0(){
        return '明天就可以摸大奶子';
    }

    public function activity1(){
        return '你正在跟大奶子乳交';
    }
}