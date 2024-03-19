<?php

namespace App\Http\Controllers;

use Exception;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\DB;

class StatusController extends BaseController
{
    public function status(): Response
    {
        try {
            DB::getPdo();
        } catch (Exception $e) {
            return response('', Response::HTTP_SERVICE_UNAVAILABLE);
        }

        return response('', Response::HTTP_OK);
    }
}
