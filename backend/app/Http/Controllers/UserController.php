<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * @OA\Get(
     *     path="/user",
     *     summary="get user index",
     *     description="get list users for test",
     *     @OA\Response(response=200, description="sucucess get user index")
     * )
     */
    public function index()
    {
        //
    }
}
