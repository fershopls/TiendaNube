<?php

namespace lib\Util;

class PlainTextWriter
{
    protected $fields = array();

    public function load ($arrayFields)
    {
        $this->fields = $arrayFields;
        return $this;
    }

    public function toString ($rules)
    {
        $text = "";

        foreach ($this->fields as $fields)
        {
            $_type = $fields['__type__'];

            if (isset($rules[$_type]))
            {
                $text .= $this->solveFields($fields, $rules[$_type]);
            }

            $text .= "\n";
        }

        return $text;
    }

    public function solveFields ($fields, $rules)
    {
        $string = "";
        $_majorStart = 0;
        $_majorLength = 0;

        foreach ($rules as $rule_str)
        {
            if (!preg_match("/^\d+\|\d+$/i", $rule_str))
                continue;
            list($_start, $_length) = explode("|", $rule_str, 2);

            if ($_start > $_majorStart)
            {
                $_majorStart = $_start;
                $_majorLength = $_length;
            }
        }
        for ($i = 0; $i < $_majorStart + $_majorLength +2; $i++)
        {
            $string .= ' ';
        }


        foreach ($rules as $field => $rule_str)
        {
            if (!isset($fields[$field]) || !preg_match("/^\d+\|\d+$/i", $rule_str))
                continue;
            list($_start, $_length) = explode("|", $rule_str, 2);

            $_field = substr($fields[$field], 0, $_length);
            $string = substr_replace($string, $_field, $_start -1, $_length);
        }

        return trim($string);
    }
}
