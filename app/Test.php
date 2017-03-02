<?php

namespace App;

use App\Course;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\TooManyTestsException;
use App\Exceptions\CantRegisterForTestInAFailedCourse;

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
        } else if ($user->failed_courses->contains($this->course)){
            throw new CantRegisterForTestInAFailedCourse('Usuário reprovou nesta disciplina.', 1);
        }

        $this->users()->save($user);
    }
}
