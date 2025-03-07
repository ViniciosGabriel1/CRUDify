<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApi extends Model
{
    use HasFactory;

    // Defina os campos que podem ser preenchidos (mass assignment)
    protected $fillable = [
        'user_id',
        'api_name',
        'database_path',
        'migrations_path',
    ];

    // Defina o relacionamento com UserApiColumn
    public function columns()
    {
        return $this->hasMany(UserApiColumn::class);
    }


    public function user(){
        return $this->belongsTo(User::class);
    }
}
