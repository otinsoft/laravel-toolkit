<?php

namespace Otinsoft\Toolkit\Files;

class FileState
{
    const UPLOADING = 'UPLOADING';
    const UPLOAD_FAILED = 'UPLOAD_FAILED';
    const PROGRESSING = 'PROGRESSING';
    const COMPLETED = 'COMPLETED';
    const WARNING = 'WARNING';
    const ERROR = 'ERROR';

    public static function all(): array
    {
        return [
            self::UPLOADING,
            self::UPLOAD_FAILED,
            self::PROGRESSING,
            self::COMPLETED,
            self::WARNING,
            self::ERROR,
        ];
    }
}
