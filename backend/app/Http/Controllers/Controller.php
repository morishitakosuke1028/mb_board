<?php

namespace App\Http\Controllers;
use OpenApi\Attributes as OA;

/**
 * @OA\Info(
 *     version="1.0.0",
 *     title="Test API Document",
 *     description="Test API Document"
 * )
 *
 * @OA\Server(
 *     url="http:localhost/api",
 *     description="Local server"
 * )
 */
abstract class Controller
{
    //
}
