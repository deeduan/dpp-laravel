<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Log;

class AfterApiJsonResponse
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
        return $next($request);
    }


    // 响应发送到浏览器之后.. 写一点日志
    // 可以记录请求时间
    public function terminate($request, $response)
    {
        $response_data = $response->getContent();

        if (!is_array($response_data)) {
            $response_data = json_decode($response_data, true);
        }

        $code = $response_data['code'] ?? null;

        if ($code === 0) {
            $this->writeSuccessLog($request, $response_data);
        } else {
            $this->writeErrorLog();
        }
    }

    // 写日志.. 记录请求者的ip.. 时间  等等
    protected function writeSuccessLog($request, $response)
    {
        Log::info('response success log', $response);
    }

    // 写错误日志
    protected function writeErrorLog()
    {
        Log::info('response error log');
    }


}
