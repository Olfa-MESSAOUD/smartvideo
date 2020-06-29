<?php

namespace content\smartvideo;

use Illuminate\Database\Eloquent\Model;

class trax_xapiserver_statements extends Model
{      
    protected $table = 'trax_xapiserver_statements';
    protected $fillable = ['data'];   
    protected $primaryKey = 'id';
}