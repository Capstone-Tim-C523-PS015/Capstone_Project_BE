<?php 

namespace App\Http\Middleware;

use Closure;

class CorsMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        return $next($request)->header('Access-Control-Allow-Origin', '*')->header('Access-Control-Allow-Methods','GET', 'POST', 'PUT', 'PATCH', 'DELETE', 'OPTIONS');
        // $headers = [
        //     'Access-Control-Allow-Origin'      => '*',
        //     'Access-Control-Allow-Methods'     => 'GET, POST, OPTIONS, PUT, DELETE',
        //     'Access-Control-Allow-Credentials' => 'true',
        //     'Access-Control-Max-Age'           => '86400',
        //     // 'Access-Control-Allow-Headers'     => 'DNT,User-Agent,X-Requested-With,If-Modified-Since,Cache-Control,Content-Type,Range'
        // ];

        // if ($request->isMethod('OPTIONS'))
        // {
        //     return response()->json('{"method":"OPTIONS"}', 200, $headers);
        // }

        // $response = $next($request);
        // foreach($headers as $key => $value)
        // {
        //     $response->header($key, $value);
        // }

        // // return response()->json($response);
        // return $response;
    }
}