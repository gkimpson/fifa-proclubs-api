<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StatsController extends Controller
{
    public $apiUrl = 'https://proclubs.ea.com/api/fifa/';
    public $referer = 'https://www.ea.com/';

    public function index()
    {
        
    }

    public function clubsInfo(Request $request)
    {
        $endpoint = "clubs/info?";
        $params = [
            'platform' => $request->input('platform'),
            'clubIds' => $request->input('clubId') 
        ];      
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function careerStats(Request $request)
    {
        $endpoint = "members/career/stats?";
        $params = [
            'platform' => $request->input('platform'),
            'clubId' => $request->input('clubId') 
        ];    
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function memberStats(Request $request)
    {
        $endpoint = "members/stats?";
        $params = [
            'platform' => $request->input('platform'),
            'clubId' => $request->input('clubId') 
        ];        
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function matchStats(Request $request)
    {
        $endpoint = "clubs/matches?";
        $params = [
            'matchType' => $request->input('matchType'),    // gameType13
            'platform' => $request->input('platform'),      // ps4
            'clubIds' => $request->input('clubIds')         // 1741008
        ];
 
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function search()
    {

    }

    private function doExternalApiCall($endpoint = null, $params = null)
    {
        $url = $this->apiUrl . $endpoint . http_build_query($params);
        return Http::withHeaders(['Referer' => $this->referer])->get($url)->json();      
    }

    private function doCurl($endpoint = null, $params = [])
    {
        $url = $this->apiUrl . $endpoint . http_build_query($params);

        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
              'Referer: https://www.ea.com/'
            ),
          ));
          
          $response = curl_exec($curl);
          
          curl_close($curl);
          echo $response;
    }
}