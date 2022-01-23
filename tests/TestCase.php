<?php

namespace Tests;

use App\Models\User;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    public function loginAsUser()
    {
        $user = User::factory()->make();
        $response = $this->actingAs($user);
        return $response;
    } 
}
