<?php

namespace App\Utils;

class MinuteInterpreter
{
    const NOTUBIZ_XML = 'notubiz-xml';

    const NOT_SUPPORTED = 'not-supported';
    const FILE_NOT_FOUND = 'not-found';

    private $minuteInterpreter;
    private $minuteVersion;

    public function getMinuteInterpreter(): ?string
    {
        return $this->minuteInterpreter;
    }

    public function setMinuteInterpreter($minuteInterpreter): self
    {
        $this->minuteInterpreter = $minuteInterpreter;
        return $this;
    }

    public function getMinuteVersion(): ?string
    {
        return $this->minuteVersion;
    }

    public function setMinuteVersion($minuteVersion): self
    {
        $this->minuteVersion = $minuteVersion;
        return $this;
    }
}
