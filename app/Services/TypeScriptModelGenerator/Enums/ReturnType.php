<?php

namespace App\Services\TypeScriptModelGenerator\Enums;

enum ReturnType: string
{
    case Boolean = 'boolean';
    case Number = 'number';
    case String = 'string';
    case Null = 'null';
    case Unknown = 'unknown';
}
