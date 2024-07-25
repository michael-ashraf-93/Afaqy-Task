<?php

namespace Tests;

use Database\Seeders\TestDatabaseSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use refreshDatabase;

    public function setUp(): void
    {
        parent::setUp();
        $this->seed(TestDatabaseSeeder::class);
    }

    /**
     * @param array $queryParams
     * @return string
     */
    protected function setQuery(array $queryParams = []): string
    {
        return http_build_query($queryParams, '', '&', PHP_QUERY_RFC3986);
    }

}
