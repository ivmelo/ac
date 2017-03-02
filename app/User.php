<?php

namespace App;

use App\Course;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [];

    public function tests()
    {
        return $this->belongsToMany('App\Test');
    }

    public function courses() {
        return $this->belongsToMany('App\Course')->withPivot('status')->withTimestamps();
    }

    public function failed_courses() {
        return $this->belongsToMany('App\Course')->wherePivot('status', Course::FAILED)->withTimestamps();
    }

    public function certified_courses() {
        return $this->belongsToMany('App\Course')->wherePivot('status', Course::CERTIFIED)->withTimestamps();
    }

    public function transfered_courses() {
        return $this->belongsToMany('App\Course')->wherePivot('status', Course::TRANSFERED)->withTimestamps();
    }

    public function getCertifiedHours() {
        $courses = $this->transfered_courses;

        $total_hours = 0;

        foreach ($courses as $course) {
            $total_hours += $course->ch;
        }

        return $total_hours;
    }

    public function getTransferedHours() {
        $courses = $this->certified_courses;

        $total_hours = 0;

        foreach ($courses as $course) {
            $total_hours += $course->ch;
        }

        return $total_hours;
    }
}
