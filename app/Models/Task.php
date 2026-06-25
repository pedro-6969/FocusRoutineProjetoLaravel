<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Category;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;


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

    public function isOverdue(): bool
    {
        return $this->status === 'Pending'
            && Carbon::parse($this->task_date)->isBefore(today());
    }

    public function priorityClass(): string
    {
        return match ($this->priority) {
            'High' => 'priority-high',
            'Medium' => 'priority-medium',
            'Low' => 'priority-low',
            default => 'priority-default',
        };
    }

    public function statusClass(): string
    {
        if ($this->isOverdue()) {
            return 'task-overdue';
        }

        return $this->status === 'Completed'
            ? 'task-completed'
            : 'task-pending';
    }
}
