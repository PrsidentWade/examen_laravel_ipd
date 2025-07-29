<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable = [
        'texte',
        'auteur_id',
        'taks_id'
    ];


    public function auteur()
    {
        return $this->belongsTo(User::class, 'auteur_id');
    }

    public function tache()
    {
        return $this->belongsTo(Taks::class);
    }
}
