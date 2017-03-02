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

    /**
     * The certification tests that the user has taken.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tests()
    {
        return $this->belongsToMany('App\Test');
    }

    /**
     * The courses that this user has taken.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function courses() {
        return $this->belongsToMany('App\Course')->withPivot('status')->withTimestamps();
    }

    /**
     * The courses that this user has failed in.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function failed_courses() {
        return $this->belongsToMany('App\Course')->wherePivot('status', Course::FAILED)->withTimestamps();
    }

    /**
     * The courses that this user has certified.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function certified_courses() {
        return $this->belongsToMany('App\Course')->wherePivot('status', Course::CERTIFIED)->withTimestamps();
    }

    /**
     * The courses that were transfered by this user.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function transfered_courses() {
        return $this->belongsToMany('App\Course')->wherePivot('status', Course::TRANSFERED)->withTimestamps();
    }

    /**
     * The amount of course hours that this user has in certified courses.
     *
     * @return integer
     */
    public function getCertifiedHours() {
        return $this->certified_courses->sum('ch');
    }

    /**
     * The amount of course hours that this user has in transfered courses.
     *
     * @return integer
     */
    public function getTransferedHours() {
        return $this->transfered_courses->sum('ch');
    }

    /**
     * The total amount of course hours that this user has in certified courses.
     *
     * @return integer
     */
    public function getTotalTransferAndCertifiedHours() {
        return $this->getTransferedHours() + $this->getCertifiedHours();
    }
}
