<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'priority', 'project_id'];

    /**
     * Get the project that owns the task.
     */
    public function project() {
        return $this->belongsTo(Project::class);
    }
}
