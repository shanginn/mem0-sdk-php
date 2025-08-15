<?php

declare(strict_types=1);

namespace Mem0\Enum;

enum ApiVersion: string
{
    case V1 = 'v1';
    case V2 = 'v2';
}