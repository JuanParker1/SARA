<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Log extends MyModel
{
    protected $table = 'sara_logs';
	protected $guarded = ['id'];
}
