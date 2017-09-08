<?php
/**
 * Created by PhpStorm.
 * User: 95708
 * Date: 2017/8/18
 * Time: 15:37
 */
namespace App\Http\Middleware;

use Closure;

class Activity
{
    public function handle($request,Closure $next){
        if(time() < strtotime('2017-8-17')){
           return redirect('activity0');
        }
       return $next($request);
    }
}

