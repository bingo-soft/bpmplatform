<?php

namespace Jabe\Variable\Impl\Value;

use Jabe\Variable\Type\{
    SerializableValueTypeInterface
};
use Jabe\Variable\Value\ObjectValueInterface;
use Jabe\Variable\Type\ValueType;

class ObjectValueImpl extends AbstractTypedValue implements ObjectValueInterface
{
    protected $objectTypeName;
    protected $serializationDataFormat;
    protected $serializedValue;
    protected $isDeserialized;
    protected $deserializedValue_;

    public function __construct(
        $deserializedValue,
        ?string $serializedValue = null,
        ?string $serializationDataFormat = null,
        ?string $objectTypeName = null,
        ?bool $isDeserialized = true,
        ?bool $isTransient = null
    ) {
        $this->deserializedValue_ = $deserializedValue;
        parent::__construct($deserializedValue, ValueType::getObject());

        $this->serializedValue = $serializedValue;
        $this->serializationDataFormat = $serializationDataFormat;
        $this->objectTypeName = $objectTypeName;
        $this->isDeserialized = $isDeserialized;
        if ($isTransient !== null) {
            $this->isTransient = $isTransient;
        }
    }

    public function getSerializationDataFormat(): ?string
    {
        return $this->serializationDataFormat;
    }

    public function setSerializationDataFormat(?string $serializationDataFormat): void
    {
        $this->serializationDataFormat = $serializationDataFormat;
    }

    public function getObjectTypeName(): ?string
    {
        return $this->objectTypeName;
    }

    public function setObjectTypeName(?string $objectTypeName): void
    {
        $this->objectTypeName = $objectTypeName;
    }

    public function getValueSerialized(): ?string
    {
        return $this->serializedValue;
    }

    public function setSerializedValue(?string $serializedValue): void
    {
        $this->serializedValue = $serializedValue;
    }

    public function isDeserialized(): bool
    {
        return $this->isDeserialized;
    }

    public function getValue(?string $type = null)
    {
        if ($type !== null) {
            $value = $this->getValue();
            if (is_a($value, $type)) {
                return $this->getValue();
            } else {
                throw new \Exception("Object is not deserialized.");
            }
        }
        if ($this->isDeserialized) {
            return parent::getValue();
        } else {
            $e = new \Exception();
            $from = [];
            for ($i = 0; $i < 26; $i += 1) {
                $t = $e->getTrace()[$i];
                $from[] = sprintf("%s.%s.%s", $t["file"], $t["function"], $t["line"]);
            }
            throw new \Exception("Object is not deserialized. " . implode(" <= ", $from));
        }
    }

    public function getObjectType(): ?string
    {
        if ($this->getValue() === null) {
            return null;
        } else {
            return get_class($this->getValue());
        }
    }

    public function getType(): SerializableValueTypeInterface
    {
        return parent::getType();
    }

    public function setTransient(bool $isTransient): void
    {
        $this->isTransient = $isTransient;
    }

    public function __toString()
    {
        return "ObjectValue ["
            . "value=" . $this->value
            . ", isDeserialized=" . $this->isDeserialized
            . ", serializationDataFormat=" . $this->serializationDataFormat
            . ", objectTypeName=" . $this->objectTypeName
            . ", serializedValue=" .
            ($this->serializedValue !== null ? (strlen($this->serializedValue) . " chars") : "null")
            . ", isTransient=" . $this->isTransient
            . "]";
    }
}
