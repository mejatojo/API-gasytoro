<?php

namespace App\Model;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    protected $fillable=['idUser','idPublication','commentaire','bestAnswer'];
}
