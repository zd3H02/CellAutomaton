<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LocalCell extends Model
{
    use SoftDeletes;
    protected $table = 'local_cells';
}
