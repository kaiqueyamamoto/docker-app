<?php

declare(strict_types=1);

namespace dacoto\SetEnv;

use dacoto\SetEnv\Exceptions\KeyNotFoundException;
use dacoto\SetEnv\Workers\SetEnvFormatter;
use dacoto\SetEnv\Workers\SetEnvReader;
use dacoto\SetEnv\Workers\SetEnvWriter;
use Illuminate\Contracts\Container\Container;

class SetEnvEditor
{
    private Container $app;
    private SetEnvFormatter $formatter;
    private SetEnvReader $reader;
    private SetEnvWriter $writer;
    private $filePath;

    /**
     * SetEnvEditor constructor.
     * @param  Container  $app
     * @throws Exceptions\UnableReadFileException
     */
    public function __construct(Container $app)
    {
        $this->app = $app;
        $this->formatter = new SetEnvFormatter();
        $this->reader = new SetEnvReader($this->formatter);
        $this->writer = new SetEnvWriter($this->formatter);
        $this->load();
    }

    /**
     * @throws Exceptions\UnableReadFileException
     */
    public function load($filePath = null): SetEnvEditor
    {
        $this->resetContent();

        if (!is_null($filePath)) {
            $this->filePath = $filePath;
        } elseif (method_exists($this->app, 'environmentPath') && method_exists($this->app, 'environmentFile')) {
            $this->filePath = $this->app->environmentPath().'/'.$this->app->environmentFile();
        } else {
            $this->filePath = __DIR__.'/../../../../../../.env';
        }

        $this->reader->load($this->filePath);

        if (file_exists($this->filePath)) {
            $this->writer->setBuffer($this->getContent());

            return $this;
        }
        return $this;
    }

    protected function resetContent(): void
    {
        $this->filePath = null;
        $this->reader->load((string) null);
        $this->writer->setBuffer((string) null);
    }

    /**
     * @throws Exceptions\UnableReadFileException
     */
    public function getContent()
    {
        return $this->reader->content();
    }

    /**
     * @throws Exceptions\UnableReadFileException
     */
    public function getLines(): array
    {
        return $this->reader->lines();
    }

    /**
     * @throws KeyNotFoundException
     * @throws Exceptions\UnableReadFileException
     */
    public function getValue($key)
    {
        $allKeys = $this->getKeys([$key]);

        if (array_key_exists($key, $allKeys)) {
            return $allKeys[$key]['value'];
        }

        throw new KeyNotFoundException('Requested key not found in your file.');
    }

    /**
     * @throws Exceptions\UnableReadFileException
     */
    public function getKeys($keys = []): array
    {
        $allKeys = $this->reader->keys();

        return array_filter($allKeys, static function ($key) use ($keys) {
            if (!empty($keys)) {
                return in_array($key, $keys, true);
            }

            return true;
        }, ARRAY_FILTER_USE_KEY);
    }

    public function getBuffer()
    {
        return $this->writer->getBuffer();
    }

    public function addEmpty(): SetEnvEditor
    {
        $this->writer->appendEmptyLine();
        return $this;
    }

    public function addComment($comment): SetEnvEditor
    {
        $this->writer->appendCommentLine($comment);
        return $this;
    }

    /**
     * @throws Exceptions\UnableReadFileException
     */
    public function setKey($key, $value = null, $comment = null, $export = false): SetEnvEditor
    {
        $value = (string) $value;
        $data = [compact('key', 'value', 'comment', 'export')];
        return $this->setKeys($data);
    }

    /**
     * @throws Exceptions\UnableReadFileException
     */
    public function setKeys($data): SetEnvEditor
    {
        foreach ($data as $i => $setter) {
            if (!is_array($setter)) {
                if (!is_string($i)) {
                    continue;
                }
                $setter = [
                    'key' => $i,
                    'value' => $setter,
                ];
            }
            if (array_key_exists('key', $setter)) {
                $key = $this->formatter->formatKey($setter['key']);
                $value = $setter['value'] ?? null;
                $comment = $setter['comment'] ?? null;
                $export = array_key_exists('export', $setter) ? $setter['export'] : false;

                if (!is_file($this->filePath) || !$this->keyExists($key)) {
                    $this->writer->appendSetter($key, (string) $value, $comment, $export);
                } else {
                    $oldInfo = $this->getKeys([$key]);
                    $comment = is_null($comment) ? $oldInfo[$key]['comment'] : $comment;

                    $this->writer->updateSetter($key, (string) $value, $comment, $export);
                }
            }
        }
        return $this;
    }

    /**
     * @throws Exceptions\UnableReadFileException
     */
    public function keyExists($key): bool
    {
        $allKeys = $this->getKeys();

        return array_key_exists($key, $allKeys);
    }

    public function deleteKey($key): SetEnvEditor
    {
        $keys = [$key];
        return $this->deleteKeys($keys);
    }

    public function deleteKeys($keys = []): SetEnvEditor
    {
        foreach ($keys as $key) {
            $this->writer->deleteSetter($key);
        }
        return $this;
    }

    /**
     * @throws Exceptions\UnableWriteToFileException
     */
    public function save(): SetEnvEditor
    {
        $this->writer->save($this->filePath);
        return $this;
    }
}
