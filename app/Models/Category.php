<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Task;
use App\Models\User;

class Category extends Model
{
    protected $table = 'category';

    protected $fillable = [
        'user_id',
        'name',
        'slug',
        'color',
    ];

    public function task()
    {
        return $this->hasMany(Task::class, 'category_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
