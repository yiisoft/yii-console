<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console\Tests;

use Yiisoft\Yii\Console\ExitCode;

final class ExitCodeTest extends TestCase
{
    public function reasons(): array
    {
        return [
            ['Success', ExitCode::OK],
            ['Unspecified error', ExitCode::UNSPECIFIED_ERROR],
            ['Incorrect usage, argument or option error', ExitCode::USAGE],
            ['Error in input data', ExitCode::DATAERR],
            ['Input file not found or unreadable', ExitCode::NOINPUT],
            ['User not found', ExitCode::NOUSER],
            ['Host not found', ExitCode::NOHOST],
            ['A required service is unavailable', ExitCode::UNAVAILABLE],
            ['Internal error', ExitCode::SOFTWARE],
            ['Error making system call or using OS service', ExitCode::OSERR],
            ['Error accessing system file', ExitCode::OSFILE],
            ['Cannot create output file', ExitCode::CANTCREAT],
            ['I/O error', ExitCode::IOERR],
            ['Temporary failure', ExitCode::TEMPFAIL],
            ['Unexpected remote service behavior', ExitCode::PROTOCOL],
            ['Insufficient permissions', ExitCode::NOPERM],
            ['Configuration error', ExitCode::CONFIG],
        ];
    }

    /**
     * @dataProvider reasons
     */
    public function testGetReason(string $errorMessage, int $reason): void
    {
        $this->assertEquals($errorMessage, ExitCode::getReason($reason));
    }
}
