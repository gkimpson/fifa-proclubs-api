<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class StatsController extends Controller
{
    public $apiUrl = 'https://proclubs.ea.com/api/fifa/';
    public $referrer = 'https://www.ea.com/';

    public function index()
    {
        
    }

    public function test()
    {
        // curl 'https://proclubs.ea.com/api/fifa/clubs/matches?platform=ps4&clubIds=1741008&matchType=gameType13&maxResultCount=1' \
        // -H 'Connection: keep-alive' \
        // -H 'sec-ch-ua: "Chromium";v="92", " Not A;Brand";v="99", "Google Chrome";v="92"' \
        // -H 'accept: application/json' \
        // -H 'sec-ch-ua-mobile: ?0' \
        // -H 'User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/92.0.4515.131 Safari/537.36' \
        // -H 'Origin: https://www.ea.com' \
        // -H 'Sec-Fetch-Site: same-site' \
        // -H 'Sec-Fetch-Mode: cors' \
        // -H 'Sec-Fetch-Dest: empty' \
        // -H 'Referer: https://www.ea.com/' \
        // -H 'Accept-Language: en-GB,en-US;q=0.9,en;q=0.8' \
        // --compressed        
        // $url = $this->apiUrl . 'clubs/info';
        // $url = 'https://proclubs.ea.com/api/fifa/clubs/info?';
        $url = 'https://proclubs.ea.com/api/fifa/clubs/matches?platform=ps4&clubIds=1741008&matchType=gameType13&maxResultCount=1';
        $params = [
            'platform' => 'ps4',
            'clubIds' => 1741008,
        ];

        $response = Http::withHeaders([
            'Referrer' => 'https://www.ea.com/',
            'Origin' => 'https://www.ea.com'
        ])->get($url)->body();   
        dd($response);
    }

    public function clubsInfo()
    {
        $endpoint = "clubs/info?";
        $params = [
            'platform' => 'ps4',
            'clubIds' => 1741008
        ];        
        $this->doCurl($endpoint, $params);
    }

    public function careerStats()
    {
        $endpoint = "members/career/stats?";
        $params = [
            'platform' => 'ps4',
            'clubId' => 1741008
        ];        
        $this->doCurl($endpoint, $params);
    }

    public function memberStats()
    {
        $endpoint = "members/stats?";
        $params = [
            'platform' => 'ps4',
            'clubId' => 1741008
        ];        
        $this->doCurl($endpoint, $params);
    }

    public function matchStats()
    {
        $endpoint = "clubs/matches?";
        $params = [
            'matchType' => 'gameType13',
            'platform' => 'ps4',
            'clubIds' => 1741008
        ];
 
        $this->doCurl($endpoint, $params);
    }

    public function search()
    {

    }

    public function doCurl($endpoint = null, $params = [])
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