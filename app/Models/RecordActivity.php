<?php

namespace App\Models;

use Illuminate\Support\Arr;

trait RecordActivity
{
    public $oldAttributes = [];

    public static function bootRecordActivity()
    {


        foreach (self::recordableEvents() as $event) {
            static::$event(function ($model) use ($event): void {
                ;
                $model->recordActivity($model->activityDescription($event));
            });

            if($event === "updated"){
                static::updating(function ($model) {
                    $model->oldAttributes = $model->getOriginal();
                });
            }
        }
    }

    public function recordActivity($description): void
    {
        $this->activity()->create([
            'description' => $description,
            'changes' => $this->activityChanges(),
            'project_id' => class_basename($this) === 'Project' ? $this->id : $this->project_id,
        ]);
    }
    protected function activityChanges()
    {
        if ($this->wasChanged()) {
            return [
                'before' => Arr::except(array_diff($this->oldAttributes, $this->getAttributes()), ['updated_at']),
                'after' =>  Arr::except($this->getChanges(), ['updated_at'])
            ];
        }
    }

    public function activity(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Activity::class, 'subject');
    }

    public static function recordableEvents(): array
    {
        return static::$recordableEvents ?? ['created', 'updated'];
    }

    protected function activityDescription(string $description)
    {
        return $description = "{$description}_" . strtolower(class_basename($this));
    }
}
