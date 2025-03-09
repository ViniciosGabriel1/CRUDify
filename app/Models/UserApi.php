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
        'api_identifier',
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

    public function data()
    {
        return $this->hasMany(UserApiData::class);
    }



}
