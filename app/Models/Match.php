<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;


class Match extends Model
{
    use HasFactory;

    /**
     * expected format 
     * {"7652628":{"clubId":"7652628","name":"Banterbury CF","seasons":58,"titlesWon":"9","leaguesWon":"0","divsWon1":10,"divsWon2":6,"divsWon3":7,"divsWon4":10,"cupsWon0":"0","cupsWon1":"0","cupsWon2":"0","cupsWon3":"0","cupsWon4":"0","cupsWon5":"0","cupsWon6":"0","cupsElim0":"0","cupsElim0R1":"0","cupsElim0R2":"0","cupsElim0R3":"0","cupsElim0R4":"0","cupsElim1":"0","cupsElim1R1":"0","cupsElim1R2":"0","cupsElim1R3":"0","cupsElim1R4":"0","cupsElim2":"0","cupsElim2R1":"0","cupsElim2R2":"0","cupsElim2R3":"0","cupsElim2R4":"0","cupsElim3":"0","cupsElim3R1":"0","cupsElim3R2":"0","cupsElim3R3":"0","cupsElim3R4":"0","cupsElim4":"0","cupsElim4R1":"0","cupsElim4R2":"0","cupsElim4R3":"0","cupsElim4R4":"0","cupsElim5":"0","cupsElim5R1":"0","cupsElim5R2":"0","cupsElim5R3":"0","cupsElim5R4":"0","cupsElim6":"0","cupsElim6R1":"0","cupsElim6R2":"0","cupsElim6R3":"0","cupsElim6R4":"0","promotions":"23","holds":"20","relegations":"15","rankingPoints":"3097","prevDivision":"8","maxDivision":"9","bestDivision":3,"bestPoints":"19","curSeasonMov":"-2","lastMatch0":"0","lastMatch1":"1","lastMatch2":"0","lastMatch3":"0","lastMatch4":"0","lastMatch5":"2","lastMatch6":"0","lastMatch7":"2","lastMatch8":"2","lastMatch9":"0","lastOpponent0":"29170576","lastOpponent1":"5380178","lastOpponent2":"8943438","lastOpponent3":"11008663","lastOpponent4":"1045093","lastOpponent5":"28004734","lastOpponent6":"-1","lastOpponent7":"-1","lastOpponent8":"-1","lastOpponent9":"-1","starLevel":"9","cupRankingPoints":"0","overallRankingPoints":"3097","alltimeGoals":"1423","alltimeGoalsAgainst":"1671","seasonWins":"1","seasonTies":"1","seasonLosses":"4","gamesPlayed":"6","goals":"16","goalsAgainst":"24","points":"4","prevSeasonWins":"1","prevSeasonTies":"1","prevSeasonLosses":"3","prevPoints":"4","prevProjectedPts":"8","skill":"27","wins":"205","ties":"68","losses":"280","currentDivision":2,"projectedPoints":6,"totalCupsWon":0,"recentResults":["losses","ties","losses","losses","losses","wins","","","",""],"totalGames":553,"clubInfo":{"name":"Banterbury CF","clubId":7652628,"regionId":5456198,"teamId":1318,"customKit":{"stadName":"El Alcoraz","kitId":"21594112","isCustomTeam":"1","customKitId":"7670","customAwayKitId":"7665","customKeeperKitId":"5032","kitColor1":"16718517","kitColor2":"16777215","kitColor3":"0","kitAColor1":"0","kitAColor2":"16777215","kitAColor3":"16734520","dCustomKit":"0","crestColor":"16777215","crestAssetId":"99090201"}}},"210113":{"clubId":"210113","name":"Banterbury FC","seasons":48,"titlesWon":"9","leaguesWon":"0","divsWon1":10,"divsWon2":6,"divsWon3":8,"divsWon4":10,"cupsWon0":"1","cupsWon1":"2","cupsWon2":"1","cupsWon3":"1","cupsWon4":"0","cupsWon5":"0","cupsWon6":"1","cupsElim0":"8","cupsElim0R1":"2","cupsElim0R2":"2","cupsElim0R3":"1","cupsElim0R4":"3","cupsElim1":"2","cupsElim1R1":"0","cupsElim1R2":"2","cupsElim1R3":"0","cupsElim1R4":"0","cupsElim2":"3","cupsElim2R1":"3","cupsElim2R2":"0","cupsElim2R3":"0","cupsElim2R4":"0","cupsElim3":"8","cupsElim3R1":"0","cupsElim3R2":"5","cupsElim3R3":"3","cupsElim3R4":"0","cupsElim4":"2","cupsElim4R1":"0","cupsElim4R2":"0","cupsElim4R3":"1","cupsElim4R4":"1","cupsElim5":"3","cupsElim5R1":"2","cupsElim5R2":"1","cupsElim5R3":"0","cupsElim5R4":"0","cupsElim6":"13","cupsElim6R1":"4","cupsElim6R2":"7","cupsElim6R3":"2","cupsElim6R4":"0","promotions":"18","holds":"21","relegations":"9","rankingPoints":"4741","prevDivision":"10","maxDivision":"10","bestDivision":1,"bestPoints":"21","curSeasonMov":"7","lastMatch0":"1","lastMatch1":"2","lastMatch2":"0","lastMatch3":"1","lastMatch4":"2","lastMatch5":"2","lastMatch6":"1","lastMatch7":"2","lastMatch8":"2","lastMatch9":"2","lastOpponent0":"16806936","lastOpponent1":"4334303","lastOpponent2":"13206658","lastOpponent3":"7642563","lastOpponent4":"850423","lastOpponent5":"20264331","lastOpponent6":"-1","lastOpponent7":"-1","lastOpponent8":"-1","lastOpponent9":"-1","starLevel":"9","cupRankingPoints":"245","overallRankingPoints":"4986","alltimeGoals":"976","alltimeGoalsAgainst":"868","seasonWins":"0","seasonTies":"0","seasonLosses":"0","gamesPlayed":"0","goals":"0","goalsAgainst":"0","points":"0","prevSeasonWins":"6","prevSeasonTies":"2","prevSeasonLosses":"1","prevPoints":"20","prevProjectedPts":"22","skill":"29","wins":"200","ties":"73","losses":"166","currentDivision":1,"projectedPoints":-1,"totalCupsWon":6,"recentResults":["","","","","","","","","",""],"totalGames":439,"clubInfo":{"name":"Banterbury FC","clubId":210113,"regionId":4543827,"teamId":1880,"customKit":{"stadName":"Coliseum Alfonso P\u00e9rez","kitId":"30801920","isCustomTeam":"1","customKitId":"7517","customAwayKitId":"7623","customKeeperKitId":"5006","kitColor1":"16777215","kitColor2":"1987272","kitColor3":"0","kitAColor1":"6366622","kitAColor2":"1462584","kitAColor3":"11767040","dCustomKit":"1","crestColor":"16777215","crestAssetId":"99140207"}}},"1741008":{"clubId":"1741008","name":"BanterburyFC","seasons":115,"titlesWon":"20","leaguesWon":"0","divsWon1":10,"divsWon2":-6,"divsWon3":8,"divsWon4":10,"cupsWon0":"1","cupsWon1":"0","cupsWon2":"1","cupsWon3":"0","cupsWon4":"0","cupsWon5":"0","cupsWon6":"0","cupsElim0":"14","cupsElim0R1":"5","cupsElim0R2":"5","cupsElim0R3":"4","cupsElim0R4":"0","cupsElim1":"2","cupsElim1R1":"0","cupsElim1R2":"0","cupsElim1R3":"0","cupsElim1R4":"2","cupsElim2":"5","cupsElim2R1":"2","cupsElim2R2":"1","cupsElim2R3":"2","cupsElim2R4":"0","cupsElim3":"0","cupsElim3R1":"0","cupsElim3R2":"0","cupsElim3R3":"0","cupsElim3R4":"0","cupsElim4":"0","cupsElim4R1":"0","cupsElim4R2":"0","cupsElim4R3":"0","cupsElim4R4":"0","cupsElim5":"0","cupsElim5R1":"0","cupsElim5R2":"0","cupsElim5R3":"0","cupsElim5R4":"0","cupsElim6":"6","cupsElim6R1":"1","cupsElim6R2":"1","cupsElim6R3":"2","cupsElim6R4":"2","promotions":"40","holds":"42","relegations":"33","rankingPoints":"6963","prevDivision":"9","maxDivision":"9","bestDivision":2,"bestPoints":"7","curSeasonMov":"-2","lastMatch0":"0","lastMatch1":"0","lastMatch2":"0","lastMatch3":"0","lastMatch4":"0","lastMatch5":"1","lastMatch6":"0","lastMatch7":"0","lastMatch8":"2","lastMatch9":"2","lastOpponent0":"26262769","lastOpponent1":"3024978","lastOpponent2":"27021409","lastOpponent3":"15676456","lastOpponent4":"25206729","lastOpponent5":"24822619","lastOpponent6":"-1","lastOpponent7":"-1","lastOpponent8":"-1","lastOpponent9":"-1","starLevel":"10","cupRankingPoints":"75","overallRankingPoints":"7038","alltimeGoals":"2348","alltimeGoalsAgainst":"2503","seasonWins":"0","seasonTies":"0","seasonLosses":"2","gamesPlayed":"2","goals":"2","goalsAgainst":"7","points":"0","prevSeasonWins":"0","prevSeasonTies":"0","prevSeasonLosses":"1","prevPoints":"0","prevProjectedPts":"-1","skill":"30","wins":"421","ties":"172","losses":"494","currentDivision":3,"projectedPoints":-1,"totalCupsWon":2,"recentResults":["losses","losses","","","","","","","",""],"totalGames":1087,"clubInfo":{"name":"BanterburyFC","clubId":1741008,"regionId":4344147,"teamId":112092,"customKit":{"stadName":"Wanda Metropolitano","kitId":"1836515329","isCustomTeam":"0","customKitId":"7623","customAwayKitId":"7623","customKeeperKitId":"5012","kitColor1":"1987272","kitColor2":"0","kitColor3":"16777215","kitAColor1":"16734520","kitAColor2":"0","kitAColor3":"16777215","dCustomKit":"0","crestColor":"1987272","crestAssetId":"99040402"}}},"1781228":{"clubId":"1781228","name":"BanterburyKFC","seasons":26,"titlesWon":"9","leaguesWon":"0","divsWon1":10,"divsWon2":4,"divsWon3":9,"divsWon4":10,"cupsWon0":"0","cupsWon1":"1","cupsWon2":"0","cupsWon3":"1","cupsWon4":"2","cupsWon5":"0","cupsWon6":"0","cupsElim0":"6","cupsElim0R1":"3","cupsElim0R2":"3","cupsElim0R3":"0","cupsElim0R4":"0","cupsElim1":"1","cupsElim1R1":"0","cupsElim1R2":"1","cupsElim1R3":"0","cupsElim1R4":"0","cupsElim2":"2","cupsElim2R1":"2","cupsElim2R2":"0","cupsElim2R3":"0","cupsElim2R4":"0","cupsElim3":"3","cupsElim3R1":"0","cupsElim3R2":"2","cupsElim3R3":"1","cupsElim3R4":"0","cupsElim4":"1","cupsElim4R1":"0","cupsElim4R2":"1","cupsElim4R3":"0","cupsElim4R4":"0","cupsElim5":"0","cupsElim5R1":"0","cupsElim5R2":"0","cupsElim5R3":"0","cupsElim5R4":"0","cupsElim6":"2","cupsElim6R1":"1","cupsElim6R2":"0","cupsElim6R3":"1","cupsElim6R4":"0","promotions":"12","holds":"9","relegations":"5","rankingPoints":"1893","prevDivision":"8","maxDivision":"8","bestDivision":3,"bestPoints":"12","curSeasonMov":"-2","lastMatch0":"0","lastMatch1":"2","lastMatch2":"0","lastMatch3":"2","lastMatch4":"1","lastMatch5":"2","lastMatch6":"0","lastMatch7":"1","lastMatch8":"0","lastMatch9":"1","lastOpponent0":"21878409","lastOpponent1":"5157923","lastOpponent2":"15789781","lastOpponent3":"29927745","lastOpponent4":"28675966","lastOpponent5":"3912903","lastOpponent6":"-1","lastOpponent7":"-1","lastOpponent8":"-1","lastOpponent9":"-1","starLevel":"8","cupRankingPoints":"422","overallRankingPoints":"2315","alltimeGoals":"642","alltimeGoalsAgainst":"623","seasonWins":"1","seasonTies":"0","seasonLosses":"1","gamesPlayed":"2","goals":"4","goalsAgainst":"8","points":"3","prevSeasonWins":"1","prevSeasonTies":"0","prevSeasonLosses":"0","prevPoints":"3","prevProjectedPts":"-1","skill":"23","wins":"103","ties":"34","losses":"106","currentDivision":3,"projectedPoints":-1,"totalCupsWon":4,"recentResults":["losses","wins","","","","","","","",""],"totalGames":243,"clubInfo":{"name":"BanterburyKFC","clubId":1781228,"regionId":4344147,"teamId":1318,"customKit":{"stadName":"Otkritie Arena","kitId":"21594113","isCustomTeam":"1","customKitId":"7517","customAwayKitId":"7636","customKeeperKitId":"5006","kitColor1":"16234451","kitColor2":"6366622","kitColor3":"707566","kitAColor1":"1647458","kitAColor2":"13179675","kitAColor3":"707566","dCustomKit":"1","crestColor":"-1","crestAssetId":"99060106"}}}}
     */
    static function formatData($data)
    {   
        $collection = collect($data);
        $results = [];

        foreach ($collection as $key => $value) {
            $results[] = [
                'matchId' => $value['matchId'],
                'timestamp' => $value['timestamp'],
                'clubs' => self::getClubsData($value['clubs']),
            ];
        }

        return dd($results);
    }

    static function getClubsData($clubs) {
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

}