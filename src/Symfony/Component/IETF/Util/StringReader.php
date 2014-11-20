<?php
namespace Symfony\Component\IETF\Util;

/**
 * @author Warnar Boekkooi <warnar@boekkooi.net>
 */
class StringReader
{
    private $cursor;

    private $string;

    private $length;

    public function __construct($string)
    {
        if (!is_string($string)) {
            throw new \InvalidArgumentException(sprintf(
                'Expecting a string, got %s', (is_object($string) ? get_class($string) : gettype($string))
            ));
        }

        $this->cursor = 0;
        $this->string = $string;
        $this->length = strlen($string);
    }

    /**
     * Returns the current character.
     *
     * @return string|null A character or null if eof
     */
    public function current()
    {
        if ($this->cursor > $this->length || $this->cursor === 0) {
            return null;
        }
        return $this->string[$this->cursor-1];
    }

    /**
     * Returns True if the string has fully been read.
     *
     * @return bool
     */
    public function eof()
    {
        return $this->cursor >= $this->length;
    }

    /**
     * Reads the next X characters from the input string and advances cursor.
     *
     * @param int $length
     * @return string|null
     */
    public function read($length = 1)
    {
        if (!is_int($length)) {
            throw new \InvalidArgumentException();
        }
        if ($this->eof()) {
            return null;
        }

        $out = substr($this->string, $this->cursor, $length);
        $this->moveCursor($length);

        return $out;
    }

    /**
     * Match a regex based on all available characters and advances cursor.
     *
     * @param $pattern
     * @return null|array Returns matches if the pattern matches, NULL if it does not
     */
    public function match($pattern)
    {
        $res = $this->peekMatch($pattern);
        if (is_array($res) && isset($res[0])) {
            $this->moveCursor(strlen($res[0]));
        }
        return $res;
    }

    /**
     * Move the cursor forward.
     *
     * @param int $length
     */
    public function moveCursor($length)
    {
        $this->cursor += $length;
    }

    /**
     * Returns the next available X characters but won't advance the cursor.
     *
     * @param int|null $length
     * @return string|null
     */
    public function peek($length = 1)
    {
        if ($this->eof()) {
            return null;
        }
        if ($length === null) {
            return substr($this->string, $this->cursor);
        }

        return substr($this->string, $this->cursor, $length);
    }

    /**
     * Match a regex based on all available characters.
     *
     * @param $pattern
     * @return null|array Returns matches if the pattern matches, NULL if it does not
     */
    public function peekMatch($pattern)
    {
        if ($this->eof()) {
            return null;
        }

        $res = preg_match($pattern, substr($this->string, $this->cursor), $matches);
        if ($res === false) {
            throw new \InvalidArgumentException();
        }
        if ($res === 0) {
            return null;
        }
        return $matches;
    }
}
