<?php

namespace App\Models\Enumerations;

abstract class Priorities
{
    const LOW = 'low';
    const MEDIUM = 'medium';
    const HIGH = 'high';

    public static $types = [
        self::LOW,
        self::MEDIUM,
        self::HIGH
    ];
}
