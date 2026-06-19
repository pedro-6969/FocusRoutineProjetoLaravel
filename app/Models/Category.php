<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Task;

class Category extends Model
{
    protected $table = 'category';

    protected $fillable = [
        'name',
        'slug',
        'color',
    ];

    public function task()
    {
        return $this->hasMany(Task::class, 'category_id');
    }
}
