<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    const REGISTERED = 0;
    const PASSED = 1;
    const FAILED = 2;
    const CERTIFIED = 3;
    const TRANSFERED = 4;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'ch',
    ];

    public function tests() {
        return $this->hasMany('App\Test');
    }

    public function users() {
        return $this->belongsToMany('App\User')->withPivot('status')->withTimestamps();
    }
}
