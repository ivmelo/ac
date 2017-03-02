<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use App\Exceptions\TooManyTestsException;

class Test extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'course', 'date',
    ];

    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    public function registerUser($user) {
        $this->users()->save($user);
    }
}
