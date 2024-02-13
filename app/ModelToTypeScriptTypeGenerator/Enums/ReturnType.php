<?php

namespace App\ModelToTypeScriptTypeGenerator\Enums;

enum ReturnType: string
{
    case Boolean = 'boolean';
    case Number = 'number';
    case String = 'string';
    case Null = 'null';
}
