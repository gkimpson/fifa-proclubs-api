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

    protected $fillable = ['club_id', 'platform_id', 'name', 'properties'];

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
}
