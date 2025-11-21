<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


class Borrow extends Model
{
    //relationships
    public function users()
    {
        return $this->belongsTo(User::class);
    }

    public function books()
    {
        return $this->belongsTo(Book::class);
    }
}
