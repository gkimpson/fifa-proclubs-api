<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StatsController extends Controller
{
    public $apiUrl = 'https://proclubs.ea.com/api/fifa/';
    public $referer = 'https://www.ea.com/';
    const PLATFORMS = [
        'xbox-series-xs',
        'xboxone',
        'ps5',
        'ps4',
        'pc'
    ];

    public function index()
    {
        
    }

    public function clubsInfo(Request $request)
    {
        $endpoint = 'clubs/info?';
        $params = [
            'platform' => $this->checkValidPlatform($request->input('platform')),
            'clubIds' => $request->input('clubIds') 
        ];      
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function careerStats(Request $request)
    {
        $endpoint = 'members/career/stats?';
        $params = [
            'platform' => $this->checkValidPlatform($request->input('platform')),
            'clubId' => $request->input('clubId')
        ];    
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function seasonstats(Request $request)
    {
        $endpoint = 'clubs/seasonalStats?';
        $params = [
            'platform' => $this->checkValidPlatform($request->input('platform')),
            'clubIds' => $request->input('clubIds')
        ];    
        return $this->doExternalApiCall($endpoint, $params);        
    }

    public function memberStats(Request $request)
    {
        $endpoint = 'members/stats?';
        $params = [
            'platform' => $this->checkValidPlatform($request->input('platform')),
            'clubId' => $request->input('clubId') 
        ];        
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function matchStats(Request $request)
    {
        $endpoint = 'clubs/matches?';
        $params = [
            'matchType' => $request->input('matchType'),                            // e.g (gameType13 = Drop-in match, gameType9 = Cup matches)
            'platform' => $this->checkValidPlatform($request->input('platform')),   // e.g ps4
            'clubIds' => $request->input('clubIds')                                 // e.g 1741008
        ];
 
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function search(Request $request)
    {
        $endpoint = 'clubs/search?';
        $params = [
            'platform' => $this->checkValidPlatform($request->input('platform')),      
            'clubName' => $request->input('clubName')       // e.g banterbury
        ];           
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function settings()
    {
        $endpoint = 'settings?';
        return $this->doExternalApiCall($endpoint);        
    }

    public function seasonalLeaderboard(Request $request)
    {
        $endpoint = 'seasonRankLeaderboard?';
        $params = [
            'platform' => $this->checkValidPlatform($request->input('platform'))
        ];            
        return $this->doExternalApiCall($endpoint, $params);          
    }

    public function clubLeaderboard(Request $request)
    {
        $endpoint = 'clubRankLeaderboard?';
        $params = [
            'platform' => $this->checkValidPlatform($request->input('platform'))
        ];            
        return $this->doExternalApiCall($endpoint, $params);          
    }

    /**
     * check valid platform has been added to request
     */
    private function checkValidPlatform($platform = null)
    {
        if (!in_array($platform, self::PLATFORMS)) {
            abort(400, 'Invalid platform');
        }
        
        return $platform;
    }

    private function doExternalApiCall($endpoint = null, $params = [])
    {
        $url = $this->apiUrl . $endpoint . http_build_query($params);
        return Http::withHeaders(['Referer' => $this->referer])->get($url)->json();      
    }

    /** 
     * @deprecated
     */
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