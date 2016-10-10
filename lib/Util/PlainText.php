<?php

namespace lib\Util;


class PlainText {

    protected $raw_data = "";
    protected $line_stack = [];
    protected $line_index = -1;

    /**
     * Loads raw content to process
     * @param $data string Raw content
     * @return $this
     */
    public function load ($data) {
        $this->raw_data = $data;
        $this->line_stack = $this->splitInLines($data);
        $this->line_index = -1;
        return $this;
    }

    /**
     * Split in lines a multiline file
     * @param $data string Raw multiline file
     * @return array Array of lines
     */
    protected function splitInLines ($data) {
        return explode(PHP_EOL, $data);
    }

    /**
     * Return line by index
     * @param $index int Line stack index number
     * @return string Line content
     */
    protected function getLine ($index) {
        if (isset($this->line_stack[$index]))
            return $this->line_stack[$index];
        else return False;
    }

    /**
     * Shift lines stack
     * @return string Line content
     */
    protected function nextLine() {
        $this->line_index++;
        return $this->getLine($this->line_index);
    }

    public function toArray($rules, $trim = True)
    {
        $rows = array();
        $is_multiline = is_array($rules[array_keys($rules)[0]])?True:False;
        $lines_per_row = count($rules);

        for ($i = 0; $i < count($this->line_stack); $i++)
        {
            $line  = $this->nextLine();
            $line_row = array();

            if ($is_multiline) {
                for ($k = 0; $k < $lines_per_row; $k++)
                {
                    $row_applied = $this->applyRulesArray($rules[array_keys($rules)[$k]], $line, $trim);
                    $line_row = array_merge($line_row, $row_applied);

                    if ($k != $lines_per_row -1)
                        $line = $this->nextLine();
                }
            } else {
                $line_row = $this->applyRulesArray($rules, $line, $trim);
            }

            $rows[] = $line_row;
        }

        return $rows;
    }

    /**
     * Substr read the content and return array
     * @param  array  $rules Array of rules
     * @param  string $str   Content
     * @param  bool   $trim  Clean the String
     * @return array Variables Processed
     */
    protected function applyRulesArray ($rules, $str, $trim) {
        $row = [];
        foreach ($rules as $input => $rule) {
            list($ini, $end) = explode('|', $rule);
            $tmp = substr($str, $ini-1, $end);
            if ($trim) $tmp = trim($tmp);
            $row[$input] = $tmp;
        }
        return $row;
    }

}