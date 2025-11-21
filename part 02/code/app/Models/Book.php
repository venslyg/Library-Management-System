<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Book extends Model
{
    //relationships
    //saying that a book belongs to an author
    public function authors()
    {
        return $this->belongsTo(Author::class);
    }
    //saying that a book can have many borrows
    public function borrows()
    {
        return $this->hasMany(Borrow::class);
    }
}
