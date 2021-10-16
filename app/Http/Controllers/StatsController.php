<?php

namespace App\Http\Controllers;

use App\Models\Schedule;
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
    public $user;
    const PLATFORMS = [
        'xbox-series-xs',
        'xboxone',
        'ps5',
        'ps4',
        'pc'
    ];

    const MYCLUB_DEFAULTS = [
        'platform' => 'ps5',
        'clubId' => '310718',
        'clubName' => 'Banterbury FC',
        'matchType' => 'gameType9' // (gameType13 = cup, gameType9 = league)
    ];

    const MATCH_TYPES = [
        'gameType9' => 'league',
        'gameType13' => 'cup'
    ];

    public function __construct()
    {
        $this->user = $user = auth()->user();
    }

    public function index()
    {
        
    }

    public function clubsInfo(Request $request)
    {
        $endpoint = 'clubs/info?';
        $params = [
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : $this->user->properties['platform'],
            'clubIds' => ($request->has('clubIds')) ? $request->input('clubIds') : $this->user->properties['clubId']
        ]; 
       
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function careerStats(Request $request, $cliParams = null)
    {           
        $endpoint = 'members/career/stats?';
        $params = [
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : self::MYCLUB_DEFAULTS['platform'],
            'clubId' => ($request->has('clubId')) ? $request->input('clubId') : self::MYCLUB_DEFAULTS['clubId']
        ]; 

        if ($cliParams) {
            $params = $cliParams;
        }        
           
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function seasonStats(Request $request)
    {
        $endpoint = 'clubs/seasonalStats?';
        $params = [
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : $this->user->properties['platform'],
            'clubIds' => ($request->has('clubIds')) ? $request->input('clubIds') : $this->user->properties['clubId']
        ];

        return $this->doExternalApiCall($endpoint, $params);        
    }

    public function memberStats(Request $request)
    {
        $endpoint = 'members/stats?';
        $params = [
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : $this->user->properties['platform'],
            'clubId' => ($request->has('clubId')) ? $request->input('clubId') : $this->user->properties['clubId']
        ];          
        return $this->doExternalApiCall($endpoint, $params);
    }

    public function matchStats(Request $request, $cliParams = null)
    {
        $endpoint = 'clubs/matches?';
        $params = [
            'matchType' => ($request->has('matchType')) ? $request->input('matchType') : self::MYCLUB_DEFAULTS['matchType'],
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : $this->user->properties['platform'],
            'clubIds' => ($request->has('clubIds')) ? $request->input('clubIds') : $this->user->properties['clubId']
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
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : $this->user->properties['platform'],
            'clubName' => ($request->has('clubName')) ? $request->input('clubName') : self::MYCLUB_DEFAULTS['clubName']
        ];

        $items = $this->doExternalApiCall($endpoint, $params);
    
        $validItems = [];
        foreach($items as $clubId => &$item) {
            if (array_key_exists('seasons', $item)) {
                if (array_key_exists('clubInfo', $item) && isset($item['clubInfo']['teamId'])) {
                    $teamId = $item['clubInfo']['teamId'];
                    $validItems[] = array(
                        'item' => $item,
                        'customCrestUrl' => "https://fifa21.content.easports.com/fifa/fltOnlineAssets/05772199-716f-417d-9fe0-988fa9899c4d/2021/fifaweb/crests/256x256/l{$teamId}.png",
                    );
                } else {
                    $validItems[] = array(
                        'item' => $item,
                        'customCrestUrl' => "https://media.contentapi.ea.com/content/dam/ea/fifa/fifa-21/pro-clubs/common/pro-clubs/crest-default.png",
                    );                    
                }

            } else {
                // team has yet to play a season so won't be a valid option
            }
        }
        dd($validItems[0]['customCrestUrl'], $validItems[1]['customCrestUrl'], $validItems[2]['customCrestUrl'], $validItems[3]['customCrestUrl'], $validItems[4]['customCrestUrl']);
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
            'platform' => ($request->has('platform')) ? $this->checkValidPlatform($request->input('platform')) : $this->user->properties['platform'],
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

    public function squad($platform, $clubId, Request $request)
    {
        $data =
        [
            'career' => $this->careerStats($request),
            'member' => $this->memberStats($request),
        ];

        dd($data);
    }

    public function squadRanking($platform, $clubId, Request $request)
    {
        $data =
        [
            'career' => $this->careerStats($request),
            'member' => $this->memberStats($request),
        ];

        dd($data);        
    }

    public function comparePlayers($platform, $clubId, Request $request)
    {
        $data =
        [
            'career' => $this->careerStats($request),
            'member' => $this->memberStats($request),
        ];

        dd($data);        
    }    

    public function matches($platform, $clubId, $match_type)
    {
        if (!in_array($match_type, self::MATCH_TYPES)) {
            abort(400, 'Incorrect match type');
        }

        dd('--matches--', $match_type);          
    }

    public function matchesForm($platform, $clubId, $match_type)
    {
        if (!in_array($match_type, self::MATCH_TYPES)) {
            abort(400, 'Incorrect match type');
        }

        dd('--matches form--', $match_type);          
    }    

    public function matchesRanking($platform, $clubId, $match_type)
    {
        if (!in_array($match_type, self::MATCH_TYPES)) {
            abort(400, 'Incorrect match type');
        }

        dd('--matches ranking--', $match_type);          
    }      

    public function compareClubs($platform, $clubId1, $clubId2)
    {
        dd('--compare clubs --', $clubId1, $clubId2);          
    }        
    
    public function compareClubsForm($platform, $clubId1, $clubId2, $match_type)
    {
        if (!in_array($match_type, self::MATCH_TYPES)) {
            abort(400, 'Incorrect match type');
        }
                
        dd('--compare clubs form --', $clubId1, $clubId2, $match_type);          
    }     

    public function compareSquads($platform, $clubId1, $clubId2)
    {
        dd('--compare squads --', $clubId1, $clubId2);          
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