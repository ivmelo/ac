<?php

namespace App;

use App\Course;
use Illuminate\Database\Eloquent\Model;
use App\Exceptions\TooManyTestsException;
use App\Exceptions\CantRegisterForTestInAFailedCourse;
use App\Exceptions\ReachedLimitCourseHoursException;

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

    /**
     * The users that have taken this test.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany('App\User');
    }

    /**
     * The course from which this test belongs.
     *
     * @return Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function course() {
        return $this->belongsTo('App\Course');
    }

    /**
     * A user to register in the certification tests.
     *
     * @param App\User $user
     * @return Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function registerUser($user) {
        if ($user->tests()->count() >= 4) {
            throw new TooManyTestsException('Usuário já cadastrado em 4 provas.', 1);
        } else if ($user->failed_courses->contains($this->course)){
            throw new CantRegisterForTestInAFailedCourse('Usuário reprovou nesta disciplina.', 1);
        } else if ($user->getTotalTransferAndCertifiedHours() >= 1.080) {
            throw new ReachedLimitCourseHoursException('Usuário chegou ao limite de carga horária aproveitada.', 1);
        }

        $this->users()->save($user);
    }
}
