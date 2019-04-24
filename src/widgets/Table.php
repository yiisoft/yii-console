<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace yii\console\widgets;

use yii\helpers\Console;
use yii\widgets\Widget;
use Yiisoft\Arrays\ArrayHelper;

/**
 * Table class displays a table in console.
 *
 * For example,
 *
 * ```php
 * $table = new Table();
 *
 * echo $table
 *     ->setHeaders(['test1', 'test2', 'test3'])
 *     ->setRows([
 *         ['col1', 'col2', 'col3'],
 *         ['col1', 'col2', ['col3-0', 'col3-1', 'col3-2']],
 *     ])
 *     ->run();
 * ```
 *
 * or
 *
 * ```php
 * echo Table::widget([
 *     'headers' => ['test1', 'test2', 'test3'],
 *     'rows' => [
 *         ['col1', 'col2', 'col3'],
 *         ['col1', 'col2', ['col3-0', 'col3-1', 'col3-2']],
 *     ],
 * ]);
 *
 * @property string $listPrefix List prefix. This property is write-only.
 * @property int $screenWidth Screen width. This property is write-only.
 *
 * @author Daniel Gomez Pan <pana_1990@hotmail.com>
 * @since 2.0.13
 */
class Table extends Widget
{
    private const DEFAULT_CONSOLE_SCREEN_WIDTH = 120;
    private const CONSOLE_SCROLLBAR_OFFSET = 3;
    public const CHAR_TOP = 'top';
    public const CHAR_TOP_MID = 'top-mid';
    public const CHAR_TOP_LEFT = 'top-left';
    public const CHAR_TOP_RIGHT = 'top-right';
    public const CHAR_BOTTOM = 'bottom';
    public const CHAR_BOTTOM_MID = 'bottom-mid';
    public const CHAR_BOTTOM_LEFT = 'bottom-left';
    public const CHAR_BOTTOM_RIGHT = 'bottom-right';
    public const CHAR_LEFT = 'left';
    public const CHAR_LEFT_MID = 'left-mid';
    public const CHAR_MID = 'mid';
    public const CHAR_MID_MID = 'mid-mid';
    public const CHAR_RIGHT = 'right';
    public const CHAR_RIGHT_MID = 'right-mid';
    public const CHAR_MIDDLE = 'middle';

    /**
     * @var array table headers
     */
    private $_headers = [];
    /**
     * @var array table rows
     */
    private $_rows = [];
    /**
     * @var array table chars
     */
    private $_chars = [
        self::CHAR_TOP => '═',
        self::CHAR_TOP_MID => '╤',
        self::CHAR_TOP_LEFT => '╔',
        self::CHAR_TOP_RIGHT => '╗',
        self::CHAR_BOTTOM => '═',
        self::CHAR_BOTTOM_MID => '╧',
        self::CHAR_BOTTOM_LEFT => '╚',
        self::CHAR_BOTTOM_RIGHT => '╝',
        self::CHAR_LEFT => '║',
        self::CHAR_LEFT_MID => '╟',
        self::CHAR_MID => '─',
        self::CHAR_MID_MID => '┼',
        self::CHAR_RIGHT => '║',
        self::CHAR_RIGHT_MID => '╢',
        self::CHAR_MIDDLE => '│',
    ];
    /**
     * @var array table column widths
     */
    private $_columnWidths = [];
    /**
     * @var int screen width
     */
    private $_screenWidth;
    /**
     * @var string list prefix
     */
    private $_listPrefix = '• ';


    /**
     * Set table headers.
     *
     * @param array $headers table headers
     * @return $this
     */
    public function setHeaders(array $headers): self
    {
        $this->_headers = array_values($headers);
        return $this;
    }

    /**
     * Set table rows.
     *
     * @param array $rows table rows
     * @return $this
     */
    public function setRows(array $rows): self
    {
        $this->_rows = array_map('array_values', $rows);
        return $this;
    }

    /**
     * Set table chars.
     *
     * @param array $chars table chars
     * @return $this
     */
    public function setChars(array $chars): self
    {
        $this->_chars = $chars;
        return $this;
    }

    /**
     * Set screen width.
     *
     * @param int $width screen width
     * @return $this
     */
    public function setScreenWidth(int $width): self
    {
        $this->_screenWidth = $width;
        return $this;
    }

    /**
     * Set list prefix.
     *
     * @param string $listPrefix list prefix
     * @return $this
     */
    public function setListPrefix(string $listPrefix): self
    {
        $this->_listPrefix = $listPrefix;
        return $this;
    }

    /**
     * @return string the rendered table
     */
    public function run(): string
    {
        $this->calculateRowsSize();
        $buffer = $this->renderSeparator(
            $this->_chars[self::CHAR_TOP_LEFT],
            $this->_chars[self::CHAR_TOP_MID],
            $this->_chars[self::CHAR_TOP],
            $this->_chars[self::CHAR_TOP_RIGHT]
        );
        // Header
        $buffer .= $this->renderRow($this->_headers,
            $this->_chars[self::CHAR_LEFT],
            $this->_chars[self::CHAR_MIDDLE],
            $this->_chars[self::CHAR_RIGHT]
        );

        // Content
        foreach ($this->_rows as $row) {
            $buffer .= $this->renderSeparator(
                $this->_chars[self::CHAR_LEFT_MID],
                $this->_chars[self::CHAR_MID_MID],
                $this->_chars[self::CHAR_MID],
                $this->_chars[self::CHAR_RIGHT_MID]
            );
            $buffer .= $this->renderRow($row,
                $this->_chars[self::CHAR_LEFT],
                $this->_chars[self::CHAR_MIDDLE],
                $this->_chars[self::CHAR_RIGHT]);
        }

        $buffer .= $this->renderSeparator(
            $this->_chars[self::CHAR_BOTTOM_LEFT],
            $this->_chars[self::CHAR_BOTTOM_MID],
            $this->_chars[self::CHAR_BOTTOM],
            $this->_chars[self::CHAR_BOTTOM_RIGHT]
        );

        return $buffer;
    }

    /**
     * Renders a row of data into a string.
     *
     * @param array $row row of data
     * @param string $spanLeft character for left border
     * @param string $spanMiddle character for middle border
     * @param string $spanRight character for right border
     * @return string
     * @see \yii\console\widgets\Table::render()
     */
    protected function renderRow(array $row, string $spanLeft, string $spanMiddle, string$spanRight): string
    {
        $size = $this->_columnWidths;

        $buffer = '';
        $arrayPointer = [];
        $finalChunk = [];
        for ($i = 0, ($max = $this->calculateRowHeight($row)) ?: $max = 1; $i < $max; $i++) {
            $buffer .= $spanLeft . ' ';
            foreach ($size as $index => $cellSize) {
                $cell = $row[$index] ?? null;
                $prefix = '';
                if ($index !== 0) {
                    $buffer .= $spanMiddle . ' ';
                }
                if (is_array($cell)) {
                    if (empty($finalChunk[$index])) {
                        $finalChunk[$index] = '';
                        $start = 0;
                        $prefix = $this->_listPrefix;
                        if (!isset($arrayPointer[$index])) {
                            $arrayPointer[$index] = 0;
                        }
                    } else {
                        $start = mb_strwidth($finalChunk[$index], $this->app->encoding);
                    }
                    $chunk = mb_substr($cell[$arrayPointer[$index]], $start, $cellSize - 4, $this->app->encoding);
                    $finalChunk[$index] .= $chunk;
                    if (isset($cell[$arrayPointer[$index] + 1]) && $finalChunk[$index] === $cell[$arrayPointer[$index]]) {
                        $arrayPointer[$index]++;
                        $finalChunk[$index] = '';
                    }
                } else {
                    $chunk = mb_substr($cell, ($cellSize * $i) - ($i * 2), $cellSize - 2, $this->app->encoding);
                }
                $chunk = $prefix . $chunk;
                $repeat = $cellSize - mb_strwidth($chunk, $this->app->encoding) - 1;
                $buffer .= $chunk;
                if ($repeat >= 0) {
                    $buffer .= str_repeat(' ', $repeat);
                }
            }
            $buffer .= "$spanRight\n";
        }

        return $buffer;
    }

    /**
     * Renders separator.
     *
     * @param string $spanLeft character for left border
     * @param string $spanMid character for middle border
     * @param string $spanMidMid character for middle-middle border
     * @param string $spanRight character for right border
     * @return string the generated separator row
     * @see \yii\console\widgets\Table::render()
     */
    protected function renderSeparator(string $spanLeft, string $spanMid, string $spanMidMid, string $spanRight): string
    {
        $separator = $spanLeft;
        foreach ($this->_columnWidths as $index => $rowSize) {
            if ($index !== 0) {
                $separator .= $spanMid;
            }
            $separator .= str_repeat($spanMidMid, $rowSize);
        }
        $separator .= $spanRight . "\n";
        return $separator;
    }

    /**
     * Calculate the size of rows to draw anchor of columns in console.
     *
     * @see \yii\console\widgets\Table::render()
     */
    protected function calculateRowsSize(): void
    {
        $this->_columnWidths = $columns = [];
        $totalWidth = 0;
        $screenWidth = $this->getScreenWidth() - self::CONSOLE_SCROLLBAR_OFFSET;

        for ($i = 0, $count = count($this->_headers); $i < $count; $i++) {
            $columns[] = ArrayHelper::getColumn($this->_rows, $i);
            $columns[$i][] = $this->_headers[$i];
        }

        foreach ($columns as $column) {
            $columnWidth = max(array_map(function ($val) {
                if (is_array($val)) {
                    $encodings = array_fill(0, count($val), $this->app->encoding);
                    return max(array_map('mb_strwidth', $val, $encodings)) + mb_strwidth($this->_listPrefix, $this->app->encoding);
                }

                return mb_strwidth($val, $this->app->encoding);
            }, $column)) + 2;
            $this->_columnWidths[] = $columnWidth;
            $totalWidth += $columnWidth;
        }

        $relativeWidth = $screenWidth / $totalWidth;

        if ($totalWidth > $screenWidth) {
            foreach ($this->_columnWidths as $j => $width) {
                $this->_columnWidths[$j] = (int) ($width * $relativeWidth);
                if ($j === count($this->_columnWidths)) {
                    $this->_columnWidths = $totalWidth;
                }
                $totalWidth -= $this->_columnWidths[$j];
            }
        }
    }

    /**
     * Calculate the height of a row.
     *
     * @param array $row
     * @return int maximum row per cell
     * @see \yii\console\widgets\Table::render()
     */
    protected function calculateRowHeight(array $row): int
    {
        $rowsPerCell = array_map(function ($size, $columnWidth) {
            if (\is_array($columnWidth)) {
                $rows = 0;
                foreach ($columnWidth as $width) {
                    $rows += ceil($width / ($size - 2));
                }

                return $rows;
            }

            return ceil($columnWidth / ($size - 2));
        }, $this->_columnWidths, array_map(function ($value) {
            if (\is_array($value)) {
                $encodings = array_fill(0, count($value), $this->app->encoding);
                return array_map('mb_strwidth', $value, $encodings);
            }

            return mb_strwidth($value, $this->app->encoding);
        }, $row)
        );

        return max($rowsPerCell);
    }

    /**
     * Getting screen width.
     * If it is not able to determine screen width, default value `123` will be set.
     *
     * @return int screen width
     */
    protected function getScreenWidth(): int
    {
        if (!$this->_screenWidth) {
            $size = Console::getScreenSize();
            $this->_screenWidth = $size[0]
                ?? self::DEFAULT_CONSOLE_SCREEN_WIDTH + self::CONSOLE_SCROLLBAR_OFFSET;
        }
        return $this->_screenWidth;
    }
}
