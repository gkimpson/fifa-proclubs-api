<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;

class BaseController extends Controller
{
    const API_URL = 'https://proclubs.ea.com/api/fifa/';
    const REFERER = 'https://www.ea.com/';
    const PLATFORMS = [
        'xbox-series-xs',
        'xboxone',
        'ps5',
        'ps4',
        'pc'
    ];
    
    static public function doExternalApiCall($endpoint = null, $params = [])
    {
        try {
            $url = self::API_URL . $endpoint . http_build_query($params);
            $response = Http::withHeaders(['Referer' => self::REFERER])->get($url);
            if ($response->successful()) {
                return $response->json();
            } elseif ($response->failed()) {
                $error = $response->json()['error']['errorname'];
                throw new \Exception($error, 1);
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
