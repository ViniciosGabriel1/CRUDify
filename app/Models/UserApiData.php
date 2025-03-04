<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApiData extends Model
{
    use HasFactory;

    protected $table = 'user_api_data'; // Nome da tabela

    // Campos que podem ser preenchidos
    protected $fillable = [
        'user_api_id',
        'user_id',
        'data',
    ];

    // Indicar que o campo 'data' é do tipo JSON
    protected $casts = [
        'data' => 'array', // Converte o campo 'data' para um array automaticamente
    ];

    // Relacionamento com a tabela 'user_apis'
    public function userApi()
    {
        return $this->belongsTo(UserApi::class);
    }

    // Relacionamento com a tabela 'users'
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
