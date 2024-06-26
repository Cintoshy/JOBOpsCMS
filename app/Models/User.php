<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'role',
        'phone_number',
        'job_position',
        'expertise',
        'google_id', 
        'avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected $casts = [
        'expertise' => 'array',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function ticketssss()
    {
        return $this->hasMany(Ticket::class, 'assigned_to');
    }
    
    public function activityLogs()
    {
        return $this->hasMany(ActivityLog::class);
    }
    
    public function assignedTickets()
    {
        return $this->belongsToMany(Ticket::class);
    }
    
    // public function tickets()
    // {
    //     return $this->belongsToMany(Ticket::class, 'ticket_user')
    //                 ->withTimestamps();
    // }

    // Optionally, if expertise is a relationship and not a direct attribute
    public function expertise()
    {
        return $this->hasOne(Expertise::class); // Adjust based on your actual data structure
    }
    
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
