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
        return $this->certified_courses->sum('ch');
    }

    public function getTransferedHours() {
        return $this->transfered_courses->sum('ch');
    }

    public function getTotalTransferAndCertifiedHours() {
        return $this->getTransferedHours() + $this->getCertifiedHours();
    }
}
