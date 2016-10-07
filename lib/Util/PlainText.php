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

    /**
     * Return array of data processed by rules
     * @param  array  $rules Array of Rules
     * @param  bool $multiline Indicates if rules are multiline or not
     * @param  int    $headers Lines in number of header occupancy
     * @param  bool   $trim  Clean the String
     * @return array         Variable to array
     */
    public function toArray($rules, $headers=0, $trim = True) {
        $rows = [];
        $is_multiline = is_array(current($rules))?True:False;

        for ($i = 0; $i < count($this->line_stack); $i++)
        {
            if ($i < $headers)
            continue;

            $line = $this->nextLine();
            $line_row = [];

            if ($is_multiline) {
                $lines_per_row = count($rules);

                for ($i = 0; $i < $lines_per_row; $i++)
                {
                    $row_applied = $this->applyRulesArray($rules[$i], $line, $trim);
                    $line_row = array_merge($line_row, $row_applied);
                    $line = $this->nextLine();
                }
            } else {
                $line_row = $this->applyRulesArray($rules, $line, $trim);
            }

            array_push($rows, $line_row);
        }

        return array_values($rows);
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