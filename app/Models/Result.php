<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Artisan;
use App\Models\User;
use Illuminate\Support\Str;

class Result extends Model
{
    use HasFactory;

    CONST PAGINATION = 15;

    // protected $fillable = ['match_id', 'home_team_id', 'away_team_id', 'home_team_goals', 'away_team_goals', 'outcome', 'match_date', 'properties', 'platform', 'media'];
    protected $guarded = [];
    protected $appends = ['my_club_home_or_away', 'team_ids', 'home_team_crest_url', 'away_team_crest_url', 'match_data', 'media_ids'];
    protected $casts = [
        'properties' => 'json'
    ];

    public function getMatchDateAttribute($value) 
    {
        return Carbon::parse($value);
    }    

    public static function getResults($properties)
    {
        if (!$properties['clubId']) {
            abort(404, 'Missing clubId');
        }

        if (!$properties['platform']) {
            abort(404, 'Missing platform');
        }        
        
        return Result::where('home_team_id', '=', $properties['clubId'])
                    ->orWhere('away_team_id', '=', $properties['clubId'])
                    ->orderBy('match_date', 'desc')
                    ->paginate(SELF::PAGINATION);
    }

    /**
     * @param array $data
     * @param array $params // matchType, platform, clubIds
     */
    public static function formatData($data, $params)
    {   
        $collection = collect($data);
        $results = [];

        foreach ($collection as $key => $value) {
            $results[] = [
                'matchId' => $value['matchId'],
                'timestamp' => $value['timestamp'],
                'clubs' => self::getClubsData($value['clubs'], $params),
                'players' => self::getPlayerData($value['players']),
                'aggregate' => $value['aggregate'],
            ];
        }

        // dd($results);
        return collect($results);
    }

    private static function getClubsData($clubs, $params) 
    {
        $clubs = collect($clubs);
        $data = [];

        foreach($clubs as $clubId => $club) {
            $seasonId = isset($clubs[$clubId]['season_id']) ? $clubs[$clubId]['season_id'] : null;

                // try to insert insert club (if this doesn't already exist)
                if ($clubId == $params['clubIds']) {
                    Club::insertUniqueClub($params, $club);
                }

                $data[] = [
                    'id' => $clubId,
                    'name' => isset($clubs[$clubId]['details']['name']) && (!empty($clubs[$clubId]['details']['name'])) ? $clubs[$clubId]['details']['name'] : 'TEAM DISBANDED',
                    'goals' => $clubs[$clubId]['goals'],
                    'goalsAgainst' => $clubs[$clubId]['goalsAgainst'],
                    'seasonId' => $seasonId,
                    'winnerByDnf' => $clubs[$clubId]['winnerByDnf'],
                    'wins' => $clubs[$clubId]['wins'],
                    'losses' => $clubs[$clubId]['losses'],
                    'ties' => $clubs[$clubId]['ties'],
                    'gameNumber' => $clubs[$clubId]['gameNumber'], 
                    'result' => $clubs[$clubId]['result'], 
                    'teamId' => isset($clubs[$clubId]['details']['teamId']) ? $clubs[$clubId]['details']['teamId'] : null,
                ];
        }

        return $data;
    }

    private static function getPlayerData($players)
    {
        $players = collect($players);
        
        foreach ($players as $clubId => $clubPlayer) {
            // loop through each player(s) for each club
            foreach ($players[$clubId] as $clubPlayer) {
                $data[$clubId][] = [
                    'assists' => $clubPlayer['assists'],
                    'cleansheetsany' => $clubPlayer['cleansheetsany'],
                    'cleansheetsdef' => $clubPlayer['cleansheetsdef'],
                    'cleansheetsgk' => $clubPlayer['cleansheetsgk'],
                    'goals' => $clubPlayer['goals'],
                    'goalsconceded' => $clubPlayer['goalsconceded'],
                    'losses' => $clubPlayer['losses'],
                    'losses' => $clubPlayer['mom'],
                    'passattempts' => $clubPlayer['passattempts'],
                    'passesmade' => $clubPlayer['passesmade'],
                    'pos' => $clubPlayer['pos'],
                    'passesmade' => $clubPlayer['passesmade'],
                    'realtimegame' => $clubPlayer['realtimegame'],
                    'realtimeidle' => $clubPlayer['realtimeidle'],
                    'redcards' => $clubPlayer['redcards'],
                    'saves' => $clubPlayer['saves'],
                    'SCORE' => $clubPlayer['SCORE'],
                    'shots' => $clubPlayer['shots'],
                    'tackleattempts' => $clubPlayer['tackleattempts'],
                    'tacklesmade' => $clubPlayer['tacklesmade'],
                    'vproattr' => self::getProAttributes($clubPlayer['vproattr']),
                    'vprohackreason' => $clubPlayer['vprohackreason'],
                    'wins' => $clubPlayer['wins'],
                    'playername' => $clubPlayer['playername'],
                    'properties' => $clubPlayer
                ];                
            }
        }

        return $data;
    }

    public static function insertUniqueMatches($matches, $platform = null, $showOutput = false)
    {
        $inserted = 0;
        $failedToInsert = 0;
        $start_time = microtime(TRUE);
        foreach ($matches as $match) {
            // check if existing match already exists in the db, if so don't re-insert this
            if (Result::where('match_id', '=', $match['matchId'])->doesntExist()) {
                $carbonDate = Carbon::now();
                $carbonDate->timestamp($match['timestamp']);
                $clubs = collect($match['clubs'])->values();

                $data = [
                    'match_id' => $match['matchId'],
                    'home_team_id' => $clubs[0]['id'],
                    'away_team_id' => $clubs[1]['id'],
                    'home_team_goals' => $clubs[0]['goals'],
                    'away_team_goals' => $clubs[1]['goals'],
                    'outcome' => self::getMatchOutcome($clubs[0]),
                    'match_date' => $carbonDate->format('Y-m-d H:i:s'),
                    'properties' => [
                        'clubs' => $match['clubs'],
                        'players' => $match['players'],
                        'aggregate' => $match['aggregate'], // aggregate is used for consistency as EA use the same naming convention - this is basically 'team stats' for that match
                    ],
                    'platform' => $platform
                ];
                
                // DB::enableQueryLog();
                // if ($showOutput) {
                //     dump($data);
                // }
                
                try {
                    Result::create($data);
                    $inserted++;

                    if ($showOutput) {
                        dump('inserted matchId: '. $match['matchId']);
                    }
                    
                 } catch (\Exception $e) {
                    dd($e);
                 }
                // dd(DB::getQueryLog());                
            }
        }
        return $inserted;
    }

    /**
     * get match outcome based on stats from 'home' team (club[0])
     * @clubData array
     * @return $outcome - home win, away win or draw
     */
    private static function getMatchOutcome($clubData)
    {
        if ($clubData['wins'] == 1) {
            $outcome = 'homewin';
        } elseif ($clubData['losses'] == 1) {
            $outcome = 'awaywin';
        } elseif ($clubData['ties'] == 1) {
            $outcome = 'draw';
        }

        return $outcome;
    }

    /**
     * todo - return player attributes in a nicer human format
     */
    private static function getProAttributes($attributes)
    {
        return $attributes;
    }

    public static function getApiResults($clubId, $platform, $gameType = 'gameType9')
    {
        $endpoint = 'clubs/matches?';
        $params = [
            'matchType' => $gameType,
            'platform' => $platform,
            'clubIds' => $clubId
        ];
        $referer = 'https://www.ea.com/';
        $url = 'https://proclubs.ea.com/api/fifa/' . $endpoint . http_build_query($params);
        return Http::withHeaders(['Referer' => $referer])->get($url)->json();
    }

    public function getMyClubHomeOrAwayAttribute()
    {
        $user = auth()->user();
        return ($this->attributes['home_team_id'] == $user->properties['clubId']) ? 'home' : 'away';
    }

    /** required for the club logos */
    public function getTeamIdsAttribute()
    {
        $properties = json_decode($this->attributes['properties']);
        $teams = [];

        if (isset($properties) && isset($properties->clubs[0])) {
            $teams = [
                'home' => "https://fifa21.content.easports.com/fifa/fltOnlineAssets/05772199-716f-417d-9fe0-988fa9899c4d/2021/fifaweb/crests/256x256/l{$properties->clubs[0]->teamId}.png",
                'away' => "https://fifa21.content.easports.com/fifa/fltOnlineAssets/05772199-716f-417d-9fe0-988fa9899c4d/2021/fifaweb/crests/256x256/l{$properties->clubs[1]->teamId}.png",
            ];
        }

        return $teams;
    }

    /**
     * get home team crest
     * @return string
     */
    public function getHomeTeamCrestUrlAttribute()
    {
        $teams = $this->getTeamIdsAttribute();
        if (isset($teams['home'])) {
            return $teams['home'];
        }

        return 'https://media.contentapi.ea.com/content/dam/ea/fifa/fifa-21/pro-clubs/common/pro-clubs/crest-default.png';   
    }

    /**
     * get away team crest
     * @return string
     */
    public function getAwayTeamCrestUrlAttribute()
    {
        $teams = $this->getTeamIdsAttribute();
        if (isset($teams['away'])) {
            return $teams['away'];
        }

        return 'https://media.contentapi.ea.com/content/dam/ea/fifa/fifa-21/pro-clubs/common/pro-clubs/crest-default.png';  
    }

    /**
     * gets aggregated match data (if available)
     */
    public function getMatchDataAttribute()
    {
        $json = json_decode($this->attributes['properties']);
        if (isset($json->aggregate)) {
            return collect($json->aggregate);
        }

        return null;
    }

    /**
     * explodes media ids into array
     */
    public function getMediaIdsAttribute()
    {
        $csv = $this->attributes['media'];
        $youtubeIds = [];
        if (!empty($csv)) {
            $youtubeIds = Str::of($csv)->explode(',');
        }

        if (is_object($youtubeIds)) {
            $youtubeIds = array_filter($youtubeIds->toArray());
        }
        
        return $youtubeIds;
    }

    /**
     * get media for club
     * @param string $platform
     * @param int $clubId
     */
    static public function getMedia($platform, $clubId)
    {
        $data = [];
        $data['pagination'] = Result::select('id', 'home_team_id', 'away_team_id', 'properties', 'media')
                ->where(function($query) use ($clubId) {
                $query->where('home_team_id', '=', $clubId)
                ->orWhere('away_team_id', '=', $clubId);
         })
         ->whereNotNull('media')
         ->orderBy('id', 'desc')
         ->paginate(5);

         $formatted = [];
         foreach($data['pagination'] as $row) {
             $formatted[] = explode(',', $row->media);
         }

         $data['formatted'] = $formatted;

         return $data;
    }

}