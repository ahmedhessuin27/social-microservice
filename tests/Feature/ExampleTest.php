<?php

declare(strict_types=1);

namespace Tests\Feature;

use Tests\TestCase;

class ExampleTest extends TestCase
{
    /**
     * The root path redirects to the API documentation.
     */
    public function test_root_redirects_to_docs(): void
    {
        $response = $this->get('/');

        $response->assertRedirect('/docs');
    }
}
