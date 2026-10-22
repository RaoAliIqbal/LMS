<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\EvaluationQuestion;

class Evaluation extends Model
{
    protected $fillable = ['title','questions','status'];



}
