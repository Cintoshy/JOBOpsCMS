<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

    public $primaryKey = 'id';

    public $fillable = ['service_location', 'request', 'priority_level', 
                        'description', 'user_id', 'is_approved', 'deadline', 'file_path',
                         'status', 'ictram_job_type_id', 'ictram_equipment_id', 'ictram_problem_id',
                        'nicmu_job_type_id', 'nicmu_equipment_id', 'nicmu_problem_id',
                        'mis_request_type_id', 'mis_job_type_id', 'mis_asname_id',
                    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function assignedUser()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
    
    public function users()
    {
        return $this->belongsToMany(User::class, 'ticket_user')
                    ->withTimestamps();
    }
    

    public function unit()
    {
        return $this->belongsTo(Unit::class);
    }

    public function jobType()
    {
        return $this->belongsTo(JobType::class);
    }

    public function problemType()
    {
        return $this->belongsTo(ProblemType::class);
    }

    

    // ICTRAM relationships
    public function ictramJobType()
    {
        return $this->belongsTo(IctramJobType::class);
    }

    public function ictramEquipment()
    {
        return $this->belongsTo(IctramEquipment::class);
    }

    public function ictramProblem()
    {
        return $this->belongsTo(IctramProblem::class);
    }

    // NICMU relationships
    public function nicmuJobType()
    {
        return $this->belongsTo(NicmuJobType::class);
    }

    public function nicmuEquipment()
    {
        return $this->belongsTo(NicmuEquipment::class);
    }

    public function nicmuProblem()
    {
        return $this->belongsTo(NicmuProblem::class);
    }

    // MIS relationships
    public function misRequestType()
    {
        return $this->belongsTo(MisRequestType::class);
    }

    public function misJobType()
    {
        return $this->belongsTo(MisJobType::class);
    }

    public function misAsname()
    {
        return $this->belongsTo(MisAsname::class);
    }
    

}
