<?php
 
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    protected $table = 'role';
    protected $primaryKey = 'idrole';
    public $timestamps = false;

    protected $fillable = ['nama_role'];

    // Relasi ke User
    public function users()
    {
        return $this->hasMany(User::class, 'idrole', 'idrole');
    }
}
