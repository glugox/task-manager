<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    /**
     * Get the tasks for the project.
     */
    public function tasks() {
        return $this->hasMany(Task::class)->orderBy('priority');
    }
}
