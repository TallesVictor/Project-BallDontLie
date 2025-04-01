<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Game extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'id',
        'date',
        'season',
        'status',
        'period',
        'time',
        'postseason',
        'home_team_score',
        'visitor_team_score',
        'home_team_id',
        'visitor_team_id',
        'datetime',
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'deleted_at',
    ];

    public function homeTeam()
    {
        return $this->belongsTo(Team::class, 'home_team_id');
    }

    public function visitorTeam()
    {
        return $this->belongsTo(Team::class, 'visitor_team_id');
    }
}
