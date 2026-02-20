<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Notifications\Notifiable;

class Blog extends Model
{
    protected $table = 'blogs';
    protected $primaryKey = 'id';
    protected $fillable = [
        'title',
        'slug',
        'body',
        'image',
        'user_id',
    ];
    public function user() {
        return $this->belongsTo(User::class);
    }

}
