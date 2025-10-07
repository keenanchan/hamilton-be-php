<?php

namespace Tests;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

/**
 * Base test case for Feature/Unit tests.
 * - Uses RegreshDatabase: runs migrations for every test class.
 */
abstract class TestCase extends BaseTestCase
{
    use CreatesApplication, RefreshDatabase;
}
