<?php

namespace Xolvio\OpenApiGenerator\Test;

use Spatie\LaravelData\Data;
use Xolvio\OpenApiGenerator\Attributes\Hidden;

class RequestDataWithHiddenField extends Data
{
    public function __construct(
        public string $visible_field,
        #[Hidden]
        public string $hidden_field,
    ) {}
}
