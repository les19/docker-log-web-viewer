<?php

namespace App\Data;

use Spatie\LaravelData\Data;

class LoggerData extends Data
{
    public function __construct(
        public string|null $message     = null,
        public string|null $level_name  = null,
        public string|null $datetime    = null,
        public array|null  $context     = [],
        public array|null  $extra       = [],
    ) {}

    public function parseChannelFromContext(): string|null
    {
        return isset($this->context['channel_name']) ? $this->context['channel_name'] : null;
    }
}
