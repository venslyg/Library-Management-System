<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Author extends Model
{
    //relationships
    public function books()
    {
        return $this->hasMany(Book::class);
    }
}
