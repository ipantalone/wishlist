<?php

use Laravel\Lumen\Testing\DatabaseMigrations;
use Laravel\Lumen\Testing\DatabaseTransactions;

class AppTest extends TestCase
{
    public function testIndex()
    {
        $this->get('/');

        $this->seeJsonEquals([
            'code' => 200,
            'message' => 'ok',
            'server_time' => \Carbon\Carbon::now()->toDateTimeLocalString()
        ]);
    }

    public function testError()
    {
        $response = $this->call('GET', '/test');
        $this->assertEquals(404, $response->status());
    }
}