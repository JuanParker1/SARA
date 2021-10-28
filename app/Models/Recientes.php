<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Recientes extends MyModel
{
    protected $table = 'sara_recientes';
	protected $guarded = ['id'];
}
