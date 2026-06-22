<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DailyQuote extends Model
{
    protected $table = 'daily_quote';

    protected $fillable = [
        'quote',
        'author',

    ];
}
