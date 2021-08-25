<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Result extends Model
{
    use HasFactory;

    protected $fillable = ['match_id', 'home_team_id', 'away_team_id', 'home_team_goals', 'away_team_goals', 'outcome', 'match_date', 'properties'];

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
        $clubs = collect($clubs)->values();
        $data = [];

        for($x = 0; $x < 2; $x++) {
            $seasonId = isset($clubs[$x]['season_id']) ? $clubs[$x]['season_id'] : null;

            if (isset($clubs[$x])) {
                $data[$x] = [
                    'id' => isset($clubs[$x]['details']['clubId']) ? $clubs[$x]['details']['clubId'] : null,
                    'name' => isset($clubs[$x]['details']['name']) ? $clubs[$x]['details']['name'] : null,
                    'goals' => $clubs[$x]['goals'],
                    'goalsAgainst' => $clubs[$x]['goalsAgainst'],
                    'seasonId' => $seasonId,
                    'winnerByDnf' => $clubs[$x]['winnerByDnf'],
                    'wins' => $clubs[$x]['wins'],
                    'losses' => $clubs[$x]['losses'],
                    'ties' => $clubs[$x]['ties'],
                    'gameNumber' => $clubs[$x]['gameNumber'], 
                    'result' => $clubs[$x]['result'], 
                    'teamId' => isset($clubs[$x]['details']['teamId']) ? $clubs[$x]['details']['teamId'] : null,
                ];
            }

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

    public static function insertUniqueMatches($matches)
    {
        $inserted = 0;
        foreach ($matches as $match) {
            // check if existing match already exists in the db, if so don't re-insert this
            if (Result::where('match_id', '=', $match['matchId'])->doesntExist()) {
                $carbonDate = Carbon::now();
                $carbonDate->timestamp($match['timestamp']);

                $data = [
                    'match_id' => $match['matchId'],
                    'home_team_id' => $match['clubs'][0]['id'],
                    'away_team_id' => $match['clubs'][1]['id'],
                    'home_team_goals' => $match['clubs'][0]['goals'],
                    'away_team_goals' => $match['clubs'][1]['goals'],
                    'outcome' => self::getMatchOutcome($match['clubs'][0]),
                    'match_date' => $carbonDate->format('Y-m-d H:i:s'),
                    'properties' => json_encode([
                        'clubs' => $match['clubs'],
                        'players' => $match['players']
                    ])
                ];
                
                // DB::enableQueryLog();
                if (Result::create($data)) {
                    $inserted++;
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

    private static function getProAttributes($attributes)
    {
        return $attributes;
    }

}