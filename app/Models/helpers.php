<?php

/**
 * [parseCamelCase description].
 *
 * @param [type] $str [description]
 *
 * @return [type]      [description]
 */
function parseCamelCase($str)
{
    return ucwords(preg_replace('/(?!^)[A-Z]{2,}(?=[A-Z][a-z])|[A-Z][a-z]|[0-9]{1,}/', ' $0', $str));
}
