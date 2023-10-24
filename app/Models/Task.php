<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use \Illuminate\Contracts\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;

class Task extends Model
{
    use HasFactory;

    protected $casts = [
        'is_done' => 'boolean',
    ];

    protected $hidden = [
        'updated_at'
    ];

    protected $fillable = [
        'title',
        'is_done',
        'creator_id'
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'creator_id');
    }

    protected static function booted() : void
    {
        static::addGlobalScope('creator', function(Builder $builder) {
            $builder->where('creator_id', Auth::id());
        });
    }

}
