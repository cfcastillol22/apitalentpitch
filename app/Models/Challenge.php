<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Challenge extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'description', 'difficulty', 'user_id', 'created_at', 'updated_at'
    ];

     //Relación con el usuario.
     public function getUser()
     {
         return $this->belongsTo(User::class);
     }
}
