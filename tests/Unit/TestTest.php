<?php

namespace Tests\Unit;

use App\Test;
use App\User;
use App\Course;
use Tests\TestCase;
use App\Exceptions\TooManyTestsException;
use App\Exceptions\ReachedLimitCourseHoursException;
use App\Exceptions\CantRegisterForTestInAFailedCourse;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;


class TestTest extends TestCase
{

    use DatabaseTransactions;

    /**
     * Testa que o usuário pode se cadastrar em testes de certificação.
     *
     * @return void
     */
    public function testUserCanRegisterInACertificationTest()
    {
        // Cria usuário usando o faker.
        $user = factory(User::class)->create();

        // Cria uma prova de certificação.
        $test = factory(Test::class)->create();

        // Registra usuário em prova de certificação.
        $test->registerUser($user);

        // Verifica se o usuário foi registrado.
        $this->assertEquals($test->users->first()->email, $user->email);
    }

    /**
     * Usuário não pode se cadastrar em mais de 4 certificações por semestre.
     *
     * @return void
     */
    public function testUserCanNotRegisterInMoreThanFourTestsAtOnce()
    {
        // Cria usuário usando o faker.
        $user = factory(User::class)->create();

        // Cria quatro provas de certificação.
        $tests = factory(Test::class, 5)->create();

        // Se inscreve em quatro disciplinas...
        $tests[0]->registerUser($user);
        $tests[1]->registerUser($user);
        $tests[2]->registerUser($user);
        $tests[3]->registerUser($user);

        // Erro ao cadastrar na quinta disciplina...
        $this->expectException(TooManyTestsException::class);
        $tests[4]->registerUser($user);
    }

    /**
     * Usuário não pode certificar uma matéria em que ele foi reprovado.
     *
     * @return void
     */
    public function testUserCanNotRegisterInATestForAFailedCourse()
    {
        // Cria usuário usando o faker.
        $user = factory(User::class)->create();

        // Cria uma disciplina usando o faker.
        $course = factory(Course::class)->create();

        // Cria um teste para a disciplina usando o faker.
        $test = factory(Test::class)->make();

        // Salva o teste associado a disciplina.
        $course->tests()->save($test);

        // Adiciona um curso ao usuário com status: REPROVADO.
        $course->users()->save($user, ['status' => Course::FAILED]);

        // Erro ao cadastrar num curso cujo o aluno foi reprovado.
        $this->expectException(CantRegisterForTestInAFailedCourse::class);
        $test->registerUser($user);
    }

    public function testUserCanNotRegisterForMoreThanHalfTheCourseHours()
    {
        // Cria usuário usando o faker.
        $user = factory(User::class)->create();

        // Adiciona disciplinas cursadas ao aluno até o limite de 1080.
        while ($user->getTotalTransferAndCertifiedHours() < 1080) {
            // Cria uma disciplina usando o faker.
            $course = factory(Course::class)->create();

            // Adiciona um curso ao usuário com status: CERTIFICADO ou TRANSFERIDO.
            $course->users()->save($user, [
                'status' => rand(1, 2) == 1 ? Course::CERTIFIED : Course::TRANSFERED
            ]);

            // Atualiza o modelo de usuário.
            $user = $user->fresh();
        }

        // Cria mais uma disciplina usando o faker.
        $course = factory(Course::class)->create();

        // Cria um teste para a disciplina usando o faker.
        $test = factory(Test::class)->make();

        // Salva o teste associado a disciplina.
        $course->tests()->save($test);

        // Erro ao registrar após atingir o limite de 1080 horas.
        $this->expectException(ReachedLimitCourseHoursException::class);
        $test->registerUser($user);
    }
}
