<?php

namespace Jabe\Impl\Form;

use Jabe\Form\FormFieldValidationConstraintInterface;

class FormFieldValidationConstraintImpl implements FormFieldValidationConstraintInterface
{
    protected $name;
    protected $configuration;

    public function __construct(?string $name, ?string $configuration)
    {
        $this->name = $name;
        $this->configuration = $configuration;
    }

    public function __serialize(): array
    {
        return [
            'name' => $this->name,
            'configuration' => serialize($this->configuration)
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->name = $data['name'];
        $this->configuration = $data['configuration'];
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function setConfiguration(?string $configuration): void
    {
        $this->configuration = $configuration;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }
}
