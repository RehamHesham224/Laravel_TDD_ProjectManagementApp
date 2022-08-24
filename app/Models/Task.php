<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    use HasFactory;
    protected $guarded =[];
    protected $touches=['project'];
    protected $casts=['completed'=>'boolean'];


    public static function boot(){
        parent::boot();
       
        static::created(function($task){
            $task->project->recordActivity('Created Task');
            
        });
    }
    public function complete(){
        
        $this->update(['completed'=>true]);
        $this->project->recordActivity('Completed Task');
    }

    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function path(){
        return "/projects/{$this->project->id }/tasks/{$this->id}" ; 
    }


}
