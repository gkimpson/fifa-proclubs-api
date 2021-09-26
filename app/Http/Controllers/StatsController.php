<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;

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

    const MYCLUB_DEFAULTS = [
        'platform' => 'ps4',
        'clubId' => '1741008',
        'clubName' => 'Banterbury',
        'matchType' => 'gameType9' // (gameType13 = cup, gameType9 = league)
    ];

    public function index()
    {
        
    }

    public function clubsInfo(Request $request)
    {
        $endpoint = 'clubs/info?';
        $params = [
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : self::MYCLUB_DEFAULTS['platform'],
            'clubIds' => ($request->has('clubIds')) ? $request->input('clubIds') : self::MYCLUB_DEFAULTS['clubId']
        ]; 
       
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function careerStats(Request $request)
    {
        $endpoint = 'members/career/stats?';
        $params = [
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : self::MYCLUB_DEFAULTS['platform'],
            'clubId' => ($request->has('clubId')) ? $request->input('clubId') : self::MYCLUB_DEFAULTS['clubId']
        ]; 
           
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function seasonStats(Request $request)
    {
        $endpoint = 'clubs/seasonalStats?';
        $params = [
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : self::MYCLUB_DEFAULTS['platform'],
            'clubIds' => ($request->has('clubIds')) ? $request->input('clubIds') : self::MYCLUB_DEFAULTS['clubId']
        ];

        return $this->doExternalApiCall($endpoint, $params);        
    }

    public function memberStats(Request $request)
    {
        $endpoint = 'members/stats?';
        $params = [
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : self::MYCLUB_DEFAULTS['platform'],
            'clubId' => ($request->has('clubId')) ? $request->input('clubId') : self::MYCLUB_DEFAULTS['clubId']
        ];          
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function matchStats(Request $request, $cliParams = null)
    {
        $endpoint = 'clubs/matches?';
        $params = [
            'matchType' => ($request->has('matchType')) ? $request->input('matchType') : self::MYCLUB_DEFAULTS['matchType'],
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : self::MYCLUB_DEFAULTS['platform'],
            'clubIds' => ($request->has('clubIds')) ? $request->input('clubIds') : self::MYCLUB_DEFAULTS['clubId']
        ];

        if ($cliParams) {
            $params = $cliParams;
        }
 
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function search(Request $request)
    {
        $endpoint = 'clubs/search?';
        $params = [
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : self::MYCLUB_DEFAULTS['platform'],    
            'clubName' => ($request->has('clubName')) ? $request->input('clubName') : self::MYCLUB_DEFAULTS['clubName']
        ];

        // $crestUrl = $this->overviewScrape($request, ['custom-crest-base-url']);
        // if (!empty($crestUrl) && array_key_exists('0', $crestUrl)) {
        //     $crestFullUrl = $crestUrl[0] . '21.png'; // need to check if this changes to 22 for fifa22
        // }
        
        // dd($crestFullUrl);
        $items = $this->doExternalApiCall($endpoint, $params);
        $validItems = [];
        $urls = [];
        // dump($items);
        foreach($items as $clubId => $item) {
            // $item[$clubId]['customCrestUrl'] = $this->getCustomCrestUrl($clubId, $params['platform']);
            // $urls[$clubId] = $this->getCustomCrestUrl($clubId, $params['platform']);
            if (array_key_exists('seasons', $item)) {
                $validItems[$clubId] = $items;
            } else {
                // team has yet to play a season so won't be a valid option
            }
        }

        foreach ($validItems as $clubId => &$validItem) {
            $urls[$clubId] = $this->getCustomCrestUrl($clubId, $params['platform']);
        }

        // dd($urls);
    }

    public function getCustomCrestUrl($clubId, $platformId)
    {
        $crestFullUrl = null;
        $crestUrl = $this->overviewScrape($clubId, $platformId, ['custom-crest-base-url']);
        
        $teamId = '';

        if (is_array($crestUrl) && !empty($crestUrl) && array_key_exists('0', $crestUrl)) {
            $crestFullUrl = $crestUrl[0] . '{$teamId}'; // append teamID here NOT clubId - use info request
        }
        dump($crestUrl);
        return $crestFullUrl;
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
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : self::MYCLUB_DEFAULTS['platform'],    
        ];

        return $this->doExternalApiCall($endpoint, $params);          
    }

    public function clubLeaderboard(Request $request)
    {
        $endpoint = 'clubRankLeaderboard?';
        $params = [
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : self::MYCLUB_DEFAULTS['platform'],    
        ];

        return $this->doExternalApiCall($endpoint, $params);          
    }

    /**
     * check valid platform has been added to request
     */
    private function checkValidPlatform($platform = null)
    {

        if (!in_array($platform, self::PLATFORMS)) {
            abort(400, "{$platform} is an Invalid platform");
        }
        
        return $platform;
    }

    private function doExternalApiCall($endpoint = null, $params = [])
    {
        $url = $this->apiUrl . $endpoint . http_build_query($params);
        return Http::withHeaders(['Referer' => $this->referer])->get($url)->json();      
    }

    public function runCommand()
    {   
        Artisan::call('matches:get');
    }

    /**
     * pc, ps4, xbox uses older generation
     * ps5, xbox series one uses newer generation
     * e.g
     * https://www.ea.com/en-gb/games/fifa/pro-clubs/ps5-xbsxs/rankings#platform=ps5
     * https://www.ea.com/en-gb/games/fifa/pro-clubs/ps4-xb1-pc/rankings#platform=ps4
     */
    private function generateGenerationURL($platformId)
    {
        $url = 'https://www.ea.com/en-gb/games/fifa/pro-clubs/';
        if (in_array($platformId, ['ps5', 'xbox-series-xs'])) {
            $url = $url . 'ps5-xbsxs/';
        } else {
            // older generation (ha pc....)
            $url = $url . 'ps4-xb1-pc/';
        }

        return $url;
    }

    /**
     * returns important info including the image URL (custom-crest-base-url which requires 21.png to be appended to it)
     */
    public function overviewScrape($clubId, $platformId, $attributes = [])
    {
        // dump($clubId);
        $client = new Client();
        $client->setServerParameter('HTTP_USER_AGENT', 'Mozilla/5.0 (X11; Linux i686; rv:78.0) Gecko/20100101 Firefox/78.0');
        $client->setServerParameter('REFERER', $this->referer);
        
        $platformId = $this->checkValidPlatform($platformId);

        $url = $this->generateGenerationURL($platformId);
        $url = $url . "overview?clubId={$clubId}&platform={$platformId}";
        dump($url);

        if (empty($attributes)) {
            $attributes = ['custom-crest-base-url', 'endpoints', 'colors', 'match-type', 'headers-labels', 'divison-labels', 'progressbar-labels', 'members-labels', 'match-labels', 'trophies-labels', 'history-labels', 'translations', 'crest-base-url', 'custom-crest-base-url', 'default-crest-url', 'loading-image', 'default-club-name'];
        }

        $crawler = $client->request('GET', $url);
        $clubProperties = $crawler
        ->filter('ea-proclub-overview')
        ->first()
        ->extract($attributes);
        // dump($clubProperties);
        return $clubProperties;
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