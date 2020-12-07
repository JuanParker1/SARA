<?php

namespace App\Models;

use App\Models\Core\MyModel;

class Feedback extends MyModel
{
    protected $table = 'sara_feedback';
    protected $guarded = ['id'];
}
