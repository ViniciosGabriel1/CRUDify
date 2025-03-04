<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserApi extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_user',
        'api_name',
    
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
