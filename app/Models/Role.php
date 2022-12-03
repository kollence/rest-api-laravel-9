<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    const ADMIN_NAME = 'admin';
    const USER_NAME = 'user';

    protected $fillable = [
        'role',
    ];

    public function user()
    {
        return $this->hasMany('App\Models\User', 'role_id');
    }
}
