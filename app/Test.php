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

    public function course() {
        return $this->belongsTo('App\Course');
    }

    public function registerUser($user) {
        if ($user->tests()->count() >= 4) {
            throw new TooManyTestsException('Usuário já cadastrado em 4 provas.', 1);
        } else {
            $this->users()->save($user);
        }
    }
}
