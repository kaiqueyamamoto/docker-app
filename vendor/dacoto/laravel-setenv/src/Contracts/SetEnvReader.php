<?php

declare(strict_types=1);

namespace dacoto\SetEnv\Contracts;

interface SetEnvReader
{
    /**
     * Load .env file
     *
     * @param  string  $filePath
     */
    public function load(string $filePath);

    /**
     * Get content of .env file
     */
    public function content();

    /**
     * Get all lines information's from content of .env file
     */
    public function lines();

    /**
     * Get all key information's in .env file
     */
    public function keys();
}
