<?php

declare(strict_types=1);

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected function setUp(): void
    {
        parent::setUp();

        // Os testes não dependem dos assets compilados pelo Vite; sem o
        // manifesto (ambiente de CI sem build) o @vite lançaria exceção 500.
        $this->withoutVite();
    }
}
