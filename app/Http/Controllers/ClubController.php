<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClubController extends BaseController
{
    public function index($platform, $clubId, $matchType = null)
    {
        try {
            $matchType = ($matchType === 'league') ? 'gameType9' : 'gameType13';

            $response = [];
            $endpoint = 'clubs/info?';
            $params = [
                'platform' => $platform,
                'clubIds' => $clubId
            ];
            $response['info'] = BaseController::doExternalApiCall($endpoint, $params);
    
            $endpoint = 'members/stats?';
            $params = [
                'platform' => $platform,
                'clubId' => $clubId
            ];        
            $response['memberStats'] = BaseController::doExternalApiCall($endpoint, $params);  
            
            $endpoint = 'members/career/stats?';
            $params = [
                'platform' => $platform,
                'clubId' => $clubId
            ];             
            $response['careerStats'] = BaseController::doExternalApiCall($endpoint, $params);

            $endpoint = 'clubs/matches?';
            $params = [
                'matchType' => $matchType,
                'platform' => $platform,
                'clubIds' => $clubId
            ];
            $response['clubMatches'] = BaseController::doExternalApiCall($endpoint, $params);

            return $response;
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function squad()
    {
        dd('squad');
    }

    public function compare()
    {
        dd('compare');
    }    
}
