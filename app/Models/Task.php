<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


class Task extends Model
{
    protected $table = 'task';

    protected $fillable = [
        'user_id',
        'category_id',
        'title',
        'description',
        'task_date',
        'start_time',
        'end_time',
        'priority',
        'status',
        'completed_at',




    ];

    public function user(): BelongsTo
    {

        return $this->belongsTo(User::class,'user_id');

    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }
}
