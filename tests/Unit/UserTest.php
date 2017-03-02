<?php

namespace Tests\Unit;

use App\Test;
use App\User;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class UserTest extends TestCase
{
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
        // cria usuário usando o faker
        $user = factory(User::class)->make();

        // cria uma prova de certificação
        $test = factory(Test::class)->make();

        $test->register($user);

        assetEqual($test->users->first()->email, $user->email);


    }
}
