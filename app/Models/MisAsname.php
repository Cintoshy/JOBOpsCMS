<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MisAsname extends Model
{
    use HasFactory;

    public $fillable = ['name'];

    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
