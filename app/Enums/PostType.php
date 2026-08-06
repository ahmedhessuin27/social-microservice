<?php

declare(strict_types=1);

namespace App\Enums;

enum PostType: string
{
    case Text = 'text';
    case Image = 'image';
    case Video = 'video';
    case MultiImage = 'multi_image';
}
