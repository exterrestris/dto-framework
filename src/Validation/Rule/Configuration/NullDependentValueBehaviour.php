<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validation\Rule\Configuration;

enum NullDependentValueBehaviour
{
    case PassIfNull;
    case PassIfValueIsNull;
    case FailIfNull;
}
