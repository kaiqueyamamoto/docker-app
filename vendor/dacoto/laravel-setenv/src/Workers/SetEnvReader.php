<?php

declare(strict_types=1);

namespace dacoto\SetEnv\Workers;

use dacoto\SetEnv\Contracts\SetEnvFormatter as SetEnvFormatterContract;
use dacoto\SetEnv\Exceptions\UnableReadFileException;

class SetEnvReader implements \dacoto\SetEnv\Contracts\SetEnvReader
{
    protected string $filePath;
    protected SetEnvFormatterContract $formatter;

    public function __construct(SetEnvFormatterContract $formatter)
    {
        $this->formatter = $formatter;
    }

    public function load(string $filePath): SetEnvReader
    {
        $this->filePath = $filePath;
        return $this;
    }

    /**
     * @throws UnableReadFileException
     */
    public function content()
    {
        $this->ensureFileIsReadable();
        return file_get_contents($this->filePath);
    }

    /**
     * @throws UnableReadFileException
     */
    protected function ensureFileIsReadable(): void
    {
        if (!is_readable($this->filePath) || !is_file($this->filePath)) {
            throw new UnableReadFileException(sprintf('Unable to read the file at %s.', $this->filePath));
        }
    }

    /**
     * @throws UnableReadFileException
     */
    public function lines(): array
    {
        $content = [];
        $lines = $this->readLinesFromFile();

        foreach ($lines as $row => $line) {
            $data = [
                'line' => $row + 1,
                'raw_data' => $line,
                'parsed_data' => $this->formatter->parseLine($line)
            ];

            $content[] = $data;
        }

        return $content;
    }

    /**
     * @throws UnableReadFileException
     */
    protected function readLinesFromFile()
    {
        $this->ensureFileIsReadable();

        $autodetect = ini_get('auto_detect_line_endings');
        ini_set('auto_detect_line_endings', '1');
        $lines = file($this->filePath, FILE_IGNORE_NEW_LINES);
        ini_set('auto_detect_line_endings', $autodetect);

        return $lines;
    }

    /**
     * @throws UnableReadFileException
     */
    public function keys(): array
    {
        $content = [];
        $lines = $this->readLinesFromFile();

        foreach ($lines as $row => $line) {
            $data = $this->formatter->parseLine($line);

            if ($data['type'] === 'setter') {
                $content[$data['key']] = [
                    'line' => $row + 1,
                    'export' => $data['export'],
                    'value' => $data['value'],
                    'comment' => $data['comment']
                ];
            }
        }

        return $content;
    }
}
