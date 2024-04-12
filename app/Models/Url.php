<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['original_url', 'hashed_url', 'expires_at', 'clicks'];

    static public function generateUniqueHash($originalUrl) : string {
        return substr(hash('sha256', $originalUrl), 0, 6);
    }
}
