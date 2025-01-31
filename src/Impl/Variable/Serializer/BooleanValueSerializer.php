<?php

namespace Jabe\Impl\Variable\Serializer;

use Jabe\Variable\Variables;
use Jabe\Variable\Impl\Value\UntypedValueImpl;
use Jabe\Variable\Type\ValueType;
use Jabe\Variable\Value\BooleanValueInterface;

class BooleanValueSerializer extends PrimitiveValueSerializer
{
    // boolean is modeled as int values
    private const TRUE = 1;
    private const FALSE = 0;

    public function __construct()
    {
        parent::__construct(ValueType::getBoolean());
    }

    public function convertToTypedValue(UntypedValueImpl $untypedValue): BooleanValueInterface
    {
        return Variables::booleanValue(json_decode($untypedValue->getValue()), $untypedValue->isTransient());
    }

    public function readValue(ValueFieldsInterface $valueFields, bool $deserializeValue, bool $asTransientValue = false): BooleanValueInterface
    {
        $boolValue = null;
        $intValue = $valueFields->getLongValue();

        if ($intValue !== null) {
            $boolValue = $intValue == self::TRUE;
        }

        return Variables::booleanValue($boolValue, $asTransientValue);
    }

    public function writeValue($variableValue, ValueFieldsInterface $valueFields): void
    {
        $intValue = null;
        $boolValue = $variableValue->getValue();

        if ($boolValue !== null) {
            $intValue = $boolValue ? self::TRUE : self::FALSE;
        }

        $valueFields->setLongValue($intValue);
    }
}
