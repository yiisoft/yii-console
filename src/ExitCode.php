<?php

declare(strict_types=1);

namespace Yiisoft\Yii\Console;

/**
 * This class provides constants for defining console command exit codes.
 *
 * The exit codes follow the codes defined in the [FreeBSD sysexits(3)](https://man.openbsd.org/sysexits) manual page.
 *
 * These constants can be used in console controllers for example like this:
 *
 * ```php
 * protected function execute(InputInterface $input, OutputInterface $output)
 * {
 *     if (!$this->isAllowedToPerformAction()) {
 *          $this->stderr('Error: ' . ExitCode::getReason(ExitCode::NOPERM));
 *          return ExitCode::NOPERM;
 *     }
 *
 *     // do something
 *
 *     return ExitCode::OK;
 * }
 * ```
 *
 * @see https://man.openbsd.org/sysexits
 */
class ExitCode
{
    /**
     * The command completed successfully.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const OK = 0;

    /**
     * The command exited with an error code that says nothing about the error.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const UNSPECIFIED_ERROR = 1;

    /**
     * The command was used incorrectly, e.g., with the wrong number of
     * arguments, a bad flag, a bad syntax in a parameter, or whatever.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const USAGE = 64;

    /**
     * The input data was incorrect in some way.  This should only be used for
     * user's data and not system files.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const DATAERR = 65;

    /**
     * An input file (not a system file) did not exist or was not readable.
     * This could also include errors like ``No message'' to a mailer (if it
     * cared to catch it).
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const NOINPUT = 66;

    /**
     * The user specified did not exist.  This might be used for mail addresses
     * or remote logins.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const NOUSER = 67;

    /**
     * The host specified did not exist.  This is used in mail addresses or
     * network requests.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const NOHOST = 68;

    /**
     * A service is unavailable.  This can occur if a support program or file
     * does not exist.  This can also be used as a catchall message when
     * something you wanted to do does not work, but you do not know why.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const UNAVAILABLE = 69;

    /**
     * An internal software error has been detected.  This should be limited to
     * non-operating system related errors as possible.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const SOFTWARE = 70;

    /**
     * An operating system error has been detected.  This is intended to be
     * used for such things as ``cannot fork'', ``cannot create pipe'', or the
     * like.  It includes things like getuid returning a user that does not
     * exist in the passwd file.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const OSERR = 71;

    /**
     * Some system file (e.g., /etc/passwd, /var/run/utx.active, etc.) does not
     * exist, cannot be opened, or has some sort of error (e.g., syntax error).
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const OSFILE = 72;

    /**
     * A (user specified) output file cannot be created.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const CANTCREAT = 73;

    /**
     * An error occurred while doing I/O on some file.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const IOERR = 74;

    /**
     * Temporary failure, indicating something that is not really an error. In
     * sendmail, this means that a mailer (e.g.) could not create a connection,
     * and the request should be reattempted later.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const TEMPFAIL = 75;

    /**
     * The remote system returned something that was ``not possible'' during a
     * protocol exchange.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const PROTOCOL = 76;

    /**
     * You did not have sufficient permission to perform the operation.  This
     * is not intended for file system problems, which should use NOINPUT or
     * CANTCREAT, but rather for higher level permissions.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const NOPERM = 77;

    /**
     * Something was found in an unconfigured or misconfigured state.
     *
     * @psalm-suppress MissingClassConstType It needs while supported PHP version is <=8.2
     */
    public const CONFIG = 78;

    /**
     * @var array<int, string> A map of reason descriptions for exit codes.
     */
    public static array $reasons = [
        self::OK => 'Success',
        self::UNSPECIFIED_ERROR => 'Unspecified error',
        self::USAGE => 'Incorrect usage, argument or option error',
        self::DATAERR => 'Error in input data',
        self::NOINPUT => 'Input file not found or unreadable',
        self::NOUSER => 'User not found',
        self::NOHOST => 'Host not found',
        self::UNAVAILABLE => 'A requied service is unavailable',
        self::SOFTWARE => 'Internal error',
        self::OSERR => 'Error making system call or using OS service',
        self::OSFILE => 'Error accessing system file',
        self::CANTCREAT => 'Cannot create output file',
        self::IOERR => 'I/O error',
        self::TEMPFAIL => 'Temporary failure',
        self::PROTOCOL => 'Unexpected remote service behavior',
        self::NOPERM => 'Insufficient permissions',
        self::CONFIG => 'Configuration error',
    ];

    /**
     * Returns a short reason text for the given exit code.
     *
     * This method uses {@see $reasons} to determine the reason for an exit code.
     *
     * @param int $exitCode One of the constants defined in this class.
     *
     * @return string The reason text, or `"Unknown exit code"` if the code is not listed in {@see $reasons}.
     */
    public static function getReason(int $exitCode): string
    {
        return static::$reasons[$exitCode] ?? 'Unknown exit code';
    }
}
