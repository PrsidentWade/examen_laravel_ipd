<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use phpDocumentor\Reflection\Types\This;
use App\Models\User;

class Project extends Model
{
    protected $fillable = [
        "nom_project",
        "description",
        "owners_id"
    ];

    // relation
    public function owner()
    {
        return $this->belongsTo(User::class, 'owners_id');
    }

    public function comment()
    {
        return $this->hasMany(Comment::class);
    }
}
