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

class Result extends Model
{
    use HasFactory;

    // protected $fillable = ['match_id', 'home_team_id', 'away_team_id', 'home_team_goals', 'away_team_goals', 'outcome', 'match_date', 'properties', 'platform_id'];
    protected $guarded = [];

    public static function getResults($properties)
    {
        if (!$properties['clubId']) {
            abort(404, 'Missing clubId');
        }
        
        return Result::where('home_team_id', '=', $properties['clubId'])
                    ->orWhere('away_team_id', '=', $properties['clubId'])
                    ->orderBy('match_date', 'desc')
                    ->get();
    }

    public static function formatData($data)
    {   
        $collection = collect($data);
        $results = [];

        foreach ($collection as $key => $value) {
            $results[] = [
                'matchId' => $value['matchId'],
                'timestamp' => $value['timestamp'],
                'clubs' => self::getClubsData($value['clubs']),
                'players' => self::getPlayerData($value['players'])
            ];
        }

        return collect($results);
    }

    private static function getClubsData($clubs) 
    {
        $clubs = collect($clubs);
        $data = [];

        foreach($clubs as $clubId => $club) {
            $seasonId = isset($clubs[$clubId]['season_id']) ? $clubs[$clubId]['season_id'] : null;

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

    public static function insertUniqueMatches($matches, $platform = null)
    {
        $inserted = 0;
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
                    'properties' => json_encode([
                        'clubs' => $match['clubs'],
                        'players' => $match['players']
                    ]),
                    'platform_id' => $platform
                ];
                
                DB::enableQueryLog();
                dump($data);
                if (Result::create($data)) {
                    $inserted++;
                    dump('inserted matchId: '. $match['matchId']);
                }
                dd(DB::getQueryLog());                        
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

}