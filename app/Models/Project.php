<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;

class Project extends Model
{
    use HasFactory , RecordActivity;
    protected $guarded=[];

    public function path(){
        return "/projects/{$this->id}";
    }
    public function owner(){
        return $this->belongsTo(User::class);
    }
    public function tasks(){
        return $this->hasMany(Task::class )->latest();
    }
    public function addTasks($tasks){
        return $this->tasks()->createMany($tasks);
    }
    public function addTask($body){
        return $this->tasks()->create(compact('body'));
    }
    public function activity(){
        return $this->hasMany(Activity::class);
    }
    public function invite(User $user){
        return $this->members()->attach($user);
    }
    public function members(){
        return $this->belongsToMany(User::class ,'project_members')->withTimestamps();
    }
    

}
