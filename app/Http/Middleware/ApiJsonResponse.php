<?php

namespace App\Http\Middleware;

use App\Exceptions\ApiResponseException;
use Closure;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpKernel\Exception\UnauthorizedHttpException;
use \Illuminate\Http\Response;

/**
 * 自定义后置中间件 统一处理api请求响应~  处理的是成功的响应
 *
 * Class ApiJsonResponse
 * @package App\Http\Middleware
 */
class ApiJsonResponse
{
    /**
     * 哪些响应异常需要被集中处理
     *
     * @var array
     */
    protected $shouldTransExceptions = [
        UnauthorizedHttpException::class,// 用于捕获jwt 异常
    ];


    /**
     * Handle an incoming request.
     *
     * @param $request
     * @param Closure $next
     * @return JsonResponse|mixed
     * @throws ApiResponseException
     */
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $this->exceptionInterceptor($response);

        // 数组响应, 直接返回.. 会处理成json
        // 导出excel, 直接返回响应
        if (is_array($response) || $response instanceof BinaryFileResponse) {
            return $response;
        }

        // 获取原始响应数据
        $origin_data = $response->getOriginalContent();
        $content = \json_decode($response->content(), true) ?? $origin_data;

        if ($content['code'] ?? 0) {
            return $response;
        }

        // 处理响应
        $data['data'] = $content['data'] ?? $content;

        // 是否存在分页响应
        if ($content['meta'] ?? []) {
            $data['meta'] = [
                'total' => $content['meta']['total'],
                'page'  => $content['meta']['page'] ?? $content['meta']['current_page'] ?? 0,
                'size'  => $content['meta']['size'] ?? $content['meta']['per_page'] ?? 0,
            ];
        }

        // 是否直接是分页对象
        if ($origin_data instanceof LengthAwarePaginator) {
            $data['meta'] = [
                'total' => $content['total'],
                'page'  => $content['current_page'],
                'size'  => (int)$content['per_page'],
            ];
        }

        $message  = ['code' => 0, 'message' => 'success', 'data' => []];
        $temp     = ($content) ? array_merge($message, $data) : $message;
        $response = $response instanceof JsonResponse ? $response->setData($temp) : $response->setContent($temp);

        return $response;
    }


    /**
     * 异常拦截器
     *
     * @param Response $response
     * @throws ApiResponseException
     */
    protected function exceptionInterceptor($response)
    {
        foreach ($this->shouldTransExceptions as $exception) {
            if ($response->exception instanceof $exception) {
                // 抛出异常
                throw new ApiResponseException(
                    $response->exception->getMessage(),
                    $response->exception->getCode() ?? 0
                );
            }
        }
    }

}
