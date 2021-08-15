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

    public function LARAVELclubsInfo()
    {
        $url = $this->apiUrl . 'clubs/info';
        $url = 'https://proclubs.ea.com/api/fifa/clubs/info';
        $url = 'https://proclubs.ea.com/api/fifa/clubs/matches?platform=ps4&clubIds=1741008&matchType=gameType9&maxResultCount=1';
        $params = [
            'platform' => 'ps4',
            'clubIds' => 1741008,
            'matchType' => 'gameType9',
            'maxResultCount' => 1
        ];

        $response = Http::withHeaders([
            'Referrer' => 'https://www.ea.com/',
        ])->get($url, $params);   
        dd($response);
    }

    public function clubsInfo()
    {
        $curl = curl_init();

        $platform = 'ps4';
        $clubId = 1741008;
        $url = "https://proclubs.ea.com/api/fifa/clubs/info?platform={$platform}&clubIds={$clubId}";

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

    public function careerStats()
    {
        $curl = curl_init();
        $platform = 'ps4';
        $clubId = 1741008;
        $url = "https://proclubs.ea.com/api/fifa/members/career/stats?platform={$platform}&clubId={$clubId}";        

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

    public function memberStats()
    {
        $curl = curl_init();
        $platform = 'ps4';
        $clubId = 1741008;
        $url = "https://proclubs.ea.com/api/fifa/members/stats?platform={$platform}&clubId={$clubId}";         

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
            'Referer: https://www.ea.com/',
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;
    }

    public function seasonalStats()
    {
        $curl = curl_init();
        $platform = 'ps4';
        $clubId = 1741008;
        $gameType = 13;
        $matchType = 13;
        $url = "https://proclubs.ea.com/api/fifa/clubs/info?platform={$platform}&clubIds={$clubId}&gameType={$gameType}&matchType={$matchType}";       

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
            'Referer: https://www.ea.com/',
          ),
        ));
        
        $response = curl_exec($curl);
        
        curl_close($curl);
        echo $response;
    }

    public function matchStats()
    {
        $curl = curl_init();
        $platform = 'ps4';
        $matchType = 'gameType13';
        $clubIds = 1741008;
        $url = "https://proclubs.ea.com/api/fifa/clubs/matches?matchType={$matchType}&platform={$platform}&clubIds={$clubIds}";

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

    public function search()
    {

    }

    public function doCurl($endpoint)
    {
        $url = $this->apiUrl . $endpoint;
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