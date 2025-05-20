<?php

namespace App\Models\Resources\Rules;

use Illuminate\Database\Eloquent\Model;

class Rules extends Model
{
    protected $table      = 'rules';
    protected $primaryKey = 'rulesId';
    protected $fillable   = ['content'];
}
