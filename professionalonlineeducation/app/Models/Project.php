<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Project
 *
 * @property $id
 * @property $user_id
 * @property $project_name
 * @property $project_description
 * @property $start_date
 * @property $end_date
 * @property $project_file
 * @property $status
 * @property $added_by
 * @property $created_at
 * @property $updated_at
 * @property $deleted_at
 *
 * @package App
 * @mixin \Illuminate\Database\Eloquent\Builder
 */
class Project extends Model
{
    use SoftDeletes;

    static $rules = [
    ];

    protected $perPage = 20;

    /**
     * Attributes that should be mass-assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id','project_name','project_description','start_date','end_date','project_file','status','added_by'];



}
