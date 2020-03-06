<?php

namespace App\Contracts;

interface MinuteProcessorInterface
{
    public function getMinuteBookmarks(array $minuteData): array;
}