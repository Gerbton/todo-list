<?php

namespace App\Models;

use App\Exceptions\TaskNotFoundException;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Database\Eloquent\Relations\BelongsTo;

class Task extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'status',
        'user_id'
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @throws TaskNotFoundException
     */
    public static function findOrFail($id, $columns = ['*'])
    {
        $instance = static::find($id, $columns);

        if (! $instance) {
            throw new TaskNotFoundException();
        }

        return $instance;
    }
}
