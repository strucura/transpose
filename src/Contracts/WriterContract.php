<?php

namespace Strucura\Transpose\Contracts;

use Illuminate\Support\Collection;

interface WriterContract
{
    public function write(Collection $types): string;
}
