<?php

namespace Jabe\History;

class IncidentStateImpl implements IncidentStateInterface
{
    public int $stateCode = 0;
    protected ?string $name;

    public function __construct(int $suspensionCode, ?string $string)
    {
        $this->stateCode = $suspensionCode;
        $this->name = $string;
    }

    public function getStateCode(): int
    {
        return $this->stateCode;
    }

    public function __toString()
    {
        return $this->name;
    }

    public static function default(): IncidentStateInterface
    {
        return new IncidentStateImpl(0, "open");
    }

    public static function resolved(): IncidentStateInterface
    {
        return new IncidentStateImpl(1, "resolved");
    }

    public static function deleted(): IncidentStateInterface
    {
        return new IncidentStateImpl(2, "deleted");
    }
}
