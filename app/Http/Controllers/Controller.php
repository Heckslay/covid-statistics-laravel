<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    /**
     * @param $content
     * @return JsonResponse
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     */
    public function success($content): JsonResponse
    {
        return response()->json($content, 200);
    }

    /**
     * @return JsonResponse
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     */
    public function noContent(): JsonResponse
    {
        return response()->json(null, 204);
    }

    /**
     * @return JsonResponse
     * @author Lasha Lomidze <lomidzelashaf@gmail.com>
     */
    public function unauthorized($message): JsonResponse
    {
        return response()->json($message, 401);
    }
}
