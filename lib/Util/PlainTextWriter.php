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
        $_majorBegin = 0;
        $_majorLength = 0;

        foreach ($rules as $id => $rule_str)
        {
            $rule = $this->getRule($rule_str);
            if (!$rule['valid'])
                continue;

            if ($rule['begin'] > $_majorBegin)
            {
                $_majorBegin = $rule['begin'];
                $_majorLength = $rule['length'];
            }
        }

        $string = str_pad("", $_majorBegin + $_majorLength);

        foreach ($rules as $field => $rule_str)
        {
            $rule = $this->getRule($rule_str);
            if (!isset($fields[$field]) || !$rule['valid'])
                continue;

            $_field = substr($fields[$field], 0, $rule['length']);
            $_field = $this->paddingText($_field, $rule);
            $string = substr_replace($string, $_field, $rule['begin'] -1, $rule['length']);
        }

        return ($string);
    }

    public function getRule ($rule)
    {
        $rule = is_array($rule)?$rule:['pos' => $rule];
        $rule['align'] = isset($rule['align'])?$rule['align']:STR_PAD_RIGHT;
        $rule['valid'] = preg_match("/^\d+\|\d+$/i", $rule['pos']);
        list($beg, $len) = explode("|", $rule['pos'], 2);
        $rule['begin'] = $beg;
        $rule['length'] = $len;
        return $rule;
    }

    public function paddingText ($text, $rule)
    {
        return str_pad($text, $rule['length'], ' ', $rule['align']);
    }
}
