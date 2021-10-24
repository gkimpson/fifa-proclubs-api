<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class Club extends Model
{
    use HasFactory;

    protected $fillable = ['club_id', 'platform_id', 'name', 'properties', 'custom_crest_url'];

    public static function formatData($data)
    {
        $collection = collect($data);
        $results = [];

        foreach ($collection as $key => $value) {
            $results[] = [
                'club_id' => $key,
                'platform_id' => 1,
                'name' => $value['name'],
                'properties' => $value
            ];
        }

        return collect($results);
    }

    public static function insertUniqueClub($params, $club, $properties = null)
    {
        $insertedClub = false;
        if (Club::where('club_id', '=', $params['clubIds'])
                ->where('platform', '=', $params['platform'])    
                ->doesntExist()) {

            // insert 'new' club if we don't have the clubId & platform combination already
            $insertedClub = Club::create([
                'club_id' => $params['clubIds'],
                'platform' => $params['platform'],
                'name' => $club['details']['name'],
                'custom_crest_url' => null,
                'properties' => $properties,
            ]);
        }

        return $insertedClub;
    }
}
