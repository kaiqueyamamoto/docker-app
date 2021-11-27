<?php

declare(strict_types=1);

namespace dacoto\SetEnv\Contracts;

interface SetEnvFormatter
{
    /**
     * Formatting the key of setter to writing
     *
     * @param  string  $key
     */
    public function formatKey(string $key);

    /**
     * Formatting the value of setter to writing
     *
     * @param  string  $value
     * @param  bool  $forceQuotes
     */
    public function formatValue(string $value, bool $forceQuotes = false);

    /**
     * Formatting the comment to writing
     *
     * @param  string  $comment
     */
    public function formatComment(string $comment);

    /**
     * Build an setter line from the individual components for writing
     *
     * @param  string  $key
     * @param  string|null  $value
     * @param  string|null  $comment  optional
     * @param  bool  $export  optional
     */
    public function formatSetterLine(string $key, string $value = null, string $comment = null, bool $export = false);

    /**
     * Normalising the key of setter to reading
     *
     * @param  string  $key
     */
    public function normaliseKey(string $key);

    /**
     * Normalising the value of setter to reading
     *
     * @param  string  $value
     * @param  string  $quote
     */
    public function normaliseValue(string $value, string $quote = '');

    /**
     * Normalising the comment to reading
     *
     * @param  string  $comment
     */
    public function normaliseComment(string $comment);

    /**
     * Parse a line into an array of type, export, key, value and comment
     *
     * @param  string  $line
     */
    public function parseLine(string $line);
}
