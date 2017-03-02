<?php

namespace Tests\Unit;

use App\Test;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testExample()
    {
        $this->assertTrue(true);
    }

    public function testUserCanRegisterInACertificationTest() {
        // Cria usuário usando o faker.
        $user = factory(User::class)->create();

        // Cria uma prova de certificação.
        $test = factory(Test::class)->create();

        // Registra usuário em prova de certificação.
        $test->registerUser($user);

        // Verifica se o usuário foi registrado.
        $this->assertEquals($test->users->first()->email, $user->email);
    }

    public function testUserCanNotRegisterInMoreThanFourTestsAtOnce() {
        // Cria usuário usando o faker.
        $user = factory(User::class)->create();

        // Cria uma prova de certificação.
        $tests = factory(Test::class)->create();

        // Registra usuário em prova de certificação.
        $test->registerUser($user);

        // Verifica se o usuário foi registrado.
        $this->assertEquals($test->users->first()->email, $user->email);
    }
}
