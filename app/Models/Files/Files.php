<?php

namespace App\Models\Files;

use Illuminate\Database\Eloquent\Model;

class Files extends Model
{
    protected $table      = 'files';
    protected $primaryKey = 'filesId';
    protected $fillable   = [
        'path', 'original_name', 'size','mime_type',  'fileable_id', 'fileable_type',
    ];

    public function fileable(){return $this->morphTo();}
}
