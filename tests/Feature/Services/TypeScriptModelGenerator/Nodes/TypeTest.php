<?php

use App\Models\Feed;
use App\Services\TypeScriptModelGenerator\Nodes\Type;

it('builds a type', function () {
    $feed = new Feed;
    $type = new Type($feed, collect());

    expect($type->toString())->toMatchSnapshot();
    expect($type->toArray())->toMatchSnapshot();
});
