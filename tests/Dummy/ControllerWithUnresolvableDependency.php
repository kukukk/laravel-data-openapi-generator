<?php

namespace Xolvio\OpenApiGenerator\Test;

use Illuminate\Routing\Controller as LaravelController;

class ControllerWithUnresolvableDependency extends LaravelController
{
    public function __construct(UnboundInterface $dependency) {}

    public function basic(): ReturnData
    {
        return new ReturnData();
    }
}
