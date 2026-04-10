<?php

declare(strict_types=1);

namespace Exterrestris\DtoFramework\Validator\Rules\Configuration;

enum NullDependentValueBehaviour
{
    case PassIfNull;
    case PassIfValueIsNull;
    case FailIfNull;
}
