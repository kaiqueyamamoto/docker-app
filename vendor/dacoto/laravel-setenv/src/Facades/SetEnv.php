<?php

declare(strict_types=1);

namespace dacoto\SetEnv\Facades;

use dacoto\SetEnv\SetEnvEditor;
use Illuminate\Support\Facades\Facade;

/**
 * @method static load($filePath = null): SetEnvEditor
 * @method static getContent()
 * @method static getLines(): array
 * @method static getValue($key)
 * @method static getKeys($keys = []): array
 * @method static getBuffer()
 * @method static addEmpty(): SetEnvEditor
 * @method static addComment($comment): SetEnvEditor
 * @method static setKey($key, $value = null, $comment = null, $export = false): SetEnvEditor
 * @method static setKeys($data): SetEnvEditor
 * @method static keyExists($key): bool
 * @method static deleteKey($key): SetEnvEditor
 * @method static deleteKeys($keys = []): SetEnvEditor
 * @method static save(): SetEnvEditor
 *
 * @see \dacoto\SetEnv\SetEnvEditor
 */
class SetEnv extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return SetEnvEditor::class;
    }
}
