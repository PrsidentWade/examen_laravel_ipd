<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Taks extends Model
{

    protected $fillable = [
        'titre',
        'description',
        'etat',
        'dealine',
        'project_id',
        'assigned_to'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class);
    }
    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
}
