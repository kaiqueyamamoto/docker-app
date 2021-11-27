<?php

declare(strict_types=1);

namespace dacoto\SetEnv\Workers;

use dacoto\SetEnv\Contracts\SetEnvFormatter as SetEnvFormatterContract;
use dacoto\SetEnv\Exceptions\UnableWriteToFileException;

class SetEnvWriter implements \dacoto\SetEnv\Contracts\SetEnvWriter
{
    protected $buffer;
    protected SetEnvFormatterContract $formatter;

    public function __construct(SetEnvFormatterContract $formatter)
    {
        $this->formatter = $formatter;
    }

    public function getBuffer()
    {
        return $this->buffer;
    }

    public function setBuffer(string $content): SetEnvWriter
    {
        if (!empty($content)) {
            $content = rtrim((string) $content).PHP_EOL;
        }
        $this->buffer = $content;
        return $this;
    }

    public function appendEmptyLine(): SetEnvWriter
    {
        return $this->appendLine();
    }

    protected function appendLine($text = null): SetEnvWriter
    {
        $this->buffer .= $text.PHP_EOL;
        return $this;
    }

    public function appendCommentLine(string $comment): SetEnvWriter
    {
        return $this->appendLine('# '.$comment);
    }

    public function appendSetter(string $key, string $value = null, string $comment = null, bool $export = false): SetEnvWriter
    {
        $line = $this->formatter->formatSetterLine($key, (string) $value, $comment, $export);

        return $this->appendLine($line);
    }

    public function updateSetter(string $key, string $value = null, string $comment = null, bool $export = false): SetEnvWriter
    {
        $pattern = "/^(export\h)?\h*{$key}=.*/m";
        $line = $this->formatter->formatSetterLine($key, (string) $value, $comment, $export);
        $this->buffer = preg_replace_callback($pattern, static function () use ($line) {
            return $line;
        }, $this->buffer);

        return $this;
    }

    public function deleteSetter(string $key): object
    {
        $pattern = "/^(export\h)?\h*{$key}=.*\n/m";
        $this->buffer = preg_replace($pattern, '', $this->buffer);

        return $this;
    }

    /**
     * @throws UnableWriteToFileException
     */
    public function save(string $filePath): SetEnvWriter
    {
        $this->ensureFileIsWritable($filePath);
        file_put_contents($filePath, $this->buffer);
        return $this;
    }

    /**
     * @throws UnableWriteToFileException
     */
    protected function ensureFileIsWritable($filePath): void
    {
        if ((is_file($filePath) && !is_writable($filePath)) || (!is_file($filePath) && !is_writable(dirname($filePath)))) {
            throw new UnableWriteToFileException(sprintf('Unable to write to the file at %s.', $filePath));
        }
    }
}
