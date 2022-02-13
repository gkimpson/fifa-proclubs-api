<?php

namespace App\Http\Controllers;

use App\Models\Result;
use App\Models\Club;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Goutte\Client;
use Illuminate\Support\Facades\DB;
use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Arr;


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

        dd($data['member']);
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
    
    public function media($platform, $clubId, Request $request)
    {
        $data =
        [
            'media' => Result::getMedia($platform, $clubId)
        ];

        dd($data);
    }    

    private function generateFutCard() 
    {
        $img = Image::make('img/futcard.jpg');
        
        $playerStats = [
            'PAC' => 96,
            'SHO' => 81,
            'PAS' => 89,
            'DRI' => 91,
            'DEF' => 55,
            'PHY' => 70,
            'POST' => 'RW',
            'NAME' => 'Carlos Blackson',
        ];

        $img->text($playerStats['NAME'], 180, 90, function($font) {
            $font->file('fonts/fifa-2006.ttf');
            $font->size(32);
            $font->color('000000');
            $font->align('center');
            $font->valign('top');
            $font->angle(0);
        });         

        $img->text($playerStats['PAC'], 95, 330, function($font) {
            $font->file('fonts/fifa-2006.ttf');
            $font->size(24);
            $font->color('000000');
            $font->align('center');
            $font->valign('top');
            $font->angle(0);
        });     

        $img->text($playerStats['SHO'], 95, 365, function($font) {
            $font->file('fonts/fifa-2006.ttf');
            $font->size(24);
            $font->color('000000');
            $font->align('center');
            $font->valign('top');
            $font->angle(0);
        });              

        $img->text($playerStats['PAS'], 95, 398, function($font) {
            $font->file('fonts/fifa-2006.ttf');
            $font->size(24);
            $font->color('000000');
            $font->align('center');
            $font->valign('top');
            $font->angle(0);
        });   

        $img->text($playerStats['DRI'], 225, 330, function($font) {
            $font->file('fonts/fifa-2006.ttf');
            $font->size(24);
            $font->color('000000');
            $font->align('center');
            $font->valign('top');
            $font->angle(0);
        });        
        
        $img->text($playerStats['DEF'], 225, 365, function($font) {
            $font->file('fonts/fifa-2006.ttf');
            $font->size(24);
            $font->color('000000');
            $font->align('center');
            $font->valign('top');
            $font->angle(0);
        });      
        
        $img->text($playerStats['PHY'], 225, 398, function($font) {
            $font->file('fonts/fifa-2006.ttf');
            $font->size(24);
            $font->color('000000');
            $font->align('center');
            $font->valign('top');
            $font->angle(0);
        });           

        $img->text($playerStats['POST'], 105, 170, function($font) {
            $font->file('fonts/fifa-2006.ttf');
            $font->size(24);
            $font->color('000000');
            $font->align('center');
            $font->valign('top');
            $font->angle(0);
        });            
    
        
        header('Content-Type: image/jpeg');
        $playerImg = Image::make('img/carl.jpg')->resize(150, 150);
        // echo $playerImg->encode('jpeg');
        // return $playerImg->response();

        $img->insert($playerImg, 'center', 30, -40);
        echo $img->encode('jpeg');
        return $img->response();
    }

    private function attribute_values($attributes, $attribute_group, $attribute_key)
    {
        // dump($attribute_group);

        $collection = collect($attribute_group)->map(function ($key) use ($attributes, $attribute_group, $attribute_key) {
            return $attributes[$key];
        })->reject(function ($key) {
            return empty($key);
        });
        
        return $collection->average();
    }

    private function formattedPlayerAttributes($attributes)
    {
        // 092|092|084|077|077|071|071|083|071|085|060|093|073|086|072|089|097|087|080|059|080|052|087|093|048|045|090|079|083|010|010|010|010|010|
        $attributes = '073|069|069|075|068|075|080|088|082|085|092|071|085|089|072|072|072|076|069|097|096|093|075|081|092|089|072|072|073|010|010|010|010|010|';
        $attributes = '092|092|084|077|077|071|071|083|071|085|060|093|073|086|072|089|097|087|080|059|080|052|087|093|048|045|090|079|083|010|010|010|010|010|';
        // dump($attributes);
        $attributes = Str::of($attributes)->explode('|')->filter()->map(function ($value) {
            return (int)$value;
        });
        dump($attributes);

        $attribute_names = [
            0 => 'Acceleration',
            1 => 'Sprint Speed',
            2 => 'Agility',
            3 => 'Balance',
            4 => 'Jumping',
            5 => 'Stamina',
            6 => 'Strength',
            7 => 'Reactions',
            8 => 'Aggression',
            9 => 'Attack Position',
            10 => 'Ball Control',
            11 => 'Dribbling',
            12 => 'Finishing',
            13 => 'Free Kick Accuracy',
            14 => 'Heading Accuracy',
            15 => 'Shot Power',
            16 => 'Long Shots',
            17 => 'Volleys',
            18 => 'Penalties',
            19 => 'Vision',
            20 => 'Crossing',
            21 => 'Long Pass',
            22 => 'Short Pass',
            23 => 'Curve',
            24 => 'Interceptions',
            25 => 'Marking',
            26 => 'Stand Tackle',
            27 => 'Slide Tackle',
            28 => '',
            29 => 'GK Diving',
            30 => 'GK Handling',
            31 => 'GK Kicking',
            32 => 'GK Reflexes',
            33 => 'GK Positioning',
        ];

        // 092|092|084|077|077|071|071|083|071|085|060|093|073|086|072|089|097|087|080|059|080|052|087|093|048|045|090|079|083|010|010|010|010|010|        
        $attribute_groups = [
            'shooting' => [12, 13, 14, 15, 16, 17, 18], // finishing, free-kick accuracy, heading accuracy, shot power, long shots, volleys, penalties
            'passing' => [19, 20, 21, 22, 23],  // vision, crossing, long pass, short pass, curve
            'dribbling' => [2, 3, 9, 10, 11], // agility, balance, attack position, ball control, dribbling
            'defending' => [24, 25, 26, 27], // interceptions, marking, stand tackle, slide tackle
            'physical' => [4, 5, 6, 7, 8], // jumping, stamina, strength, reactions, aggression
            'pace' => [0, 1], // acceleration, speed
            'goalkeeping' => [29, 30, 31, 32, 33] // GK only - diving, handling, kicking, reflexes, positioning
        ];   

        // $total = $attributes[28];
        // dump($total);
        
        $attribute_values = [];
        foreach ($attribute_groups as $attribute_key => $attribute_group) {
            $attribute_values[$attribute_key] = round($this->attribute_values($attributes, $attribute_group, $attribute_key), 0);
        }
        
        dd($attribute_values);
    }

    private function processAttributes($playerAttributes) 
    {
        $formattedPlayerAttributes = [];
        foreach ($playerAttributes as $clubId => $playerAttribute) {
            foreach ($playerAttribute as $playerId => $attributes) {
                $formattedPlayerAttributes[] = [
                    'clubId' => $clubId,
                    'attributes' => $this->formattedPlayerAttributes($attributes)
                ];
            }
        }

        // dump($formattedPlayerAttributes);
    }

    /**
     * generate club vpro attributes
     * e.g
     * 310718 => 
     *  236199621 => "092|082|075|078|074|079|090|083|088|083|091|065|068|078|068|067|064|062|081|073|086|089|058|065|093|086|067|067|067|010|010|010|010|010|"]
     */
    private function generateVirtualProAttributes($stats, $matchStats) 
    {
        $playerAttributes = [];
        foreach ($stats as $clubId => $clubStats) {
            foreach($clubStats as $playerId => $playerStats){
                $hasValidProAttrs = (property_exists($playerStats, 'vproattr') 
                && $playerStats->vproattr != 'NH');
                if ($hasValidProAttrs) {
                    $vProAttributes[$playerId] = $playerStats->vproattr;
                    $playerAttributes[$clubId] = $vProAttributes;
                }
            }
        }
        
        $this->processAttributes($playerAttributes);
        return $playerAttributes;
    }

    public function debug(Request $request)
    {
        $data = [
            'matchStats' => $this->matchStats($request),
        ];

        $stats = collect(json_decode($data['matchStats'])[0]->players);
        $matchStats = json_decode($data['matchStats']);

        if (!empty($stats)) {

            $this->generateVirtualProAttributes($stats, $matchStats);
            
            // $filtered = $stats->filter(function ($value, $key) {
            //     return $value;
            // });            

            // dd($filtered);
            // dd($filtered->all());

            // dd($stats[0]->players);

        }

        // return $this->generateFutCard();

        // $data['maxStreaks'] = Result::getMaxStreaksByClubId(310718);
        // $data['currentStreaks'] = Result::getCurrentStreak(310718);
        // dd($data);
    }

    /**
     * save youtube highlights for match
     */
    public function highlights(Request $request)
    {
        $matchId = $request->formData['matchId'];
        $url = $request->formData['youtubeURL'];
        $youtubeId = Str::remove('https://www.youtube.com/watch?v=', $url);
        $result = Result::where('match_id', $matchId)->first();
        $result->media .= $youtubeId .',';
        $result->save();
        // dd($result, $result->save());
    }

    private function doExternalApiCallLaravel($endpoint = null, $params = [])
    {
        $url = $this->apiUrl . $endpoint . http_build_query($params);
        return Http::withHeaders(['Referer' => $this->referer])->get($url)->json();      
    }    

    private function doExternalApiCall($endpoint = null, $params = [])
    {
        $url = $this->apiUrl . $endpoint . http_build_query($params);
        ray($url);
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            // CURLOPT_MAXREDIRS => 5,
            CURLOPT_TIMEOUT => 10,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2_0,
            // CURLOPT_CUSTOMREQUEST => 'GET',
            // CURLOPT_VERBOSE => false,
            CURLOPT_FAILONERROR => true,
            CURLOPT_HTTPHEADER => array(
                "accept-language: en-US,en;q=0.9,pt-BR;q=0.8,pt;q=0.7",
                "User-Agent: Mozilla/5.0 (Macintosh; Intel Mac OS X 10_15_7) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.45 Safari/537.36",
            ),
          ));

          if(curl_exec($curl) === false)
          {
              echo 'Curl error: ' . curl_error($curl);
          }
          else
          {
            //   echo "Operation completed without any errors\n";
          }          

          if(curl_errno($curl))
          {
              echo 'Curl error: ' . curl_error($curl);
          }          
          
          $response = curl_exec($curl);
          curl_close($curl);
          ray()->json($response);
          return $response;
    }
}