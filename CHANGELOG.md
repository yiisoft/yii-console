# Yii Console Change Log

## 2.3.0 January 23, 2025

- Enh #207: Add `--open` option for `serve` command (@xepozz)
- Enh #207: Print possible options for `serve` command (@xepozz)
- Enh #218: Explicitly mark nullable parameters (@alexander-schranz)

## 2.2.0 February 17, 2024

- Enh #194: Allow to use `ErrorListiner` without logger (@vjik)

## 2.1.2 December 26, 2023

- Enh #189: Add support for `symfony/console` of version `^7.0` (@vjik)

## 2.1.1 November 05, 2023

- Chg #185: Rename `params.php` to `params-console.php` (@terabytesoftw)

## 2.1.0 May 28, 2023

- Bug #172: Fix accepting `:` as command name separator, offer using it by default (@samdark)
- Bug #179: Remove duplicate messages about server address (@samdark)
- Enh #180: Enhance output of `serve` command, add `--xdebug` option for `serve` (@xepozz)

## 2.0.1 March 31, 2023

- Bug #175: Fix `serve` under Windows (@samdark)

## 2.0.0 February 17, 2023

- Chg #171: Adapt configuration group names to Yii conventions (@vjik)
- Enh #162: Explicitly add transitive dependencies `psr/event-dispatcher` and `psr/log` (@vjik)
- Enh #163: Add `workers` option to `serve` command with default of two workers under Linux (@xepozz)
- Bug #168: Fix executing the `list` command with namespace (@vjik)

## 1.3.0 July 29, 2022

- Chg: #159: Add collecting console command name to `ApplicationStartup` class (@xepozz)

## 1.2.0 July 21, 2022

- Enh #157: Add config for `serve` command (@dood-)

## 1.1.1 July 04, 2022

- Enh #156: Add support for `symfony/event-dispatcher-contracts` of version `^3.0` (@vjik)

## 1.1.0 May 03, 2022

- Chg #148: Raise the minimum PHP version to 8.0 (@rustamwin)
- Enh #149: Add bash completion for `serve` command, serve at 127.0.0.1 by default (@rustamwin)

## 1.0.1 February 11, 2022

- Enh #141: Add support for version `^6.0` for `symfony/console` package (@devanych)
- Bug #145: Add return type to `Yiisoft\Yii\Console\CommandLoader::get()` method (@devanych)

## 1.0.0 November 01, 2021

- Initial release.
