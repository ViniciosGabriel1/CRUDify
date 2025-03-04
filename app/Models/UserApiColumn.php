<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApiColumn extends Model
{
    use HasFactory;

    // Defina os campos que podem ser preenchidos (mass assignment)
    protected $fillable = [
        'user_api_id',  // Chave estrangeira
        'name',
        'type',
    ];

    // Relacionamento inverso com UserApi
    public function userApi()
    {
        return $this->belongsTo(UserApi::class);
    }
}

