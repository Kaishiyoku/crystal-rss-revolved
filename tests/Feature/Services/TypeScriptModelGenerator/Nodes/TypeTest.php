<?php

use App\Models\Feed;
use App\Services\TypeScriptModelGenerator\Nodes\Type;

it('builds a type', function () {
    expect((new Type(new Feed()))->toString())->toMatchSnapshot();
});
