<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * Class BotResponse
 *
 * @property $id
 * @property $message
 * @property $keywords
 * @property $status
 * @property $created_at
 * @property $updated_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class BotResponse extends Model
{

    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['message','keywords','status'];

    public function getKeywordsArrayAttribute()
    {
        $value = $this->keywords;
        $keywords = [];
        $value = explode(',',$value);
      //  dd($value);
        foreach ($value as $val){
            $keywords [] = $val;
        }
        return $keywords;
    }


}
