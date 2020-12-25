<?php

namespace BpmPlatform\Model\Bpmn\Impl\Instance;

use BpmPlatform\Model\Xml\ModelBuilder;
use BpmPlatform\Model\Xml\Instance\ModelElementInstanceInterface;
use BpmPlatform\Model\Xml\Impl\Instance\ModelTypeInstanceContext;
use BpmPlatform\Model\Xml\Type\ModelTypeInstanceProviderInterface;
use BpmPlatform\Model\Bpmn\Builder\ServiceTaskBuilder;
use BpmPlatform\Model\Bpmn\Instance\{
    ServiceTaskInterface,
    OperationInterface,
    TaskInterface
};

class ServiceTaskImpl extends TaskImpl implements ServiceTaskInterface
{
    protected static $implementationAttribute;
    protected static $operationRefChild;
    protected static $classAttribute;
    protected static $delegateExpressionAttribute;
    protected static $expressionAttribute;
    protected static $resultVariableAttribute;
    protected static $topicAttribute;
    protected static $typeAttribute;
    protected static $taskPriorityAttribute;

    public function __construct(ModelTypeInstanceContext $instanceContext)
    {
        parent::__construct($instanceContext);
    }

    public static function registerType(ModelBuilder $modelBuilder): void
    {
        $typeBuilder = $modelBuilder->defineType(
            ServiceTaskInterface::class,
            BpmnModelConstants::BPMN_ELEMENT_SERVICE_TASK
        )
        ->namespaceUri(BpmnModelConstants::BPMN20_NS)
        ->extendsType(TaskInterface::class)
        ->instanceProvider(
            new class extends ModelTypeInstanceProviderInterface
            {
                public function newInstance(ModelTypeInstanceContext $instanceContext): ModelElementInstanceInterface
                {
                    return new ServiceTaskImpl($instanceContext);
                }
            }
        );

        self::$implementationAttribute = $typeBuilder->stringAttribute(
            BpmnModelConstants::BPMN_ATTRIBUTE_IMPLEMENTATION
        )
        ->defaultValue("##WebService")
        ->build();

        $sequenceBuilder = $typeBuilder->sequence();

        self::$operationRefChild = $sequenceBuilder->element(OperationRef::class)
        ->qNameElementReference(OperationInterface::class)
        ->build();

        self::$classAttribute = $typeBuilder->stringAttribute(
            BpmnModelConstants::ATTRIBUTE_CLASS
        )
        ->namespace(BpmnModelConstants::NS)
        ->build();

        self::$delegateExpressionAttribute = $typeBuilder->stringAttribute(
            BpmnModelConstants::ATTRIBUTE_DELEGATE_EXPRESSION
        )
        ->namespace(BpmnModelConstants::NS)
        ->build();

        self::$expressionAttribute = $typeBuilder->stringAttribute(
            BpmnModelConstants::ATTRIBUTE_EXPRESSION
        )
        ->namespace(BpmnModelConstants::NS)
        ->build();

        self::$resultVariableAttribute = $typeBuilder->stringAttribute(
            BpmnModelConstants::ATTRIBUTE_RESULT_VARIABLE
        )
        ->namespace(BpmnModelConstants::NS)
        ->build();

        self::$topicAttribute = $typeBuilder->stringAttribute(
            BpmnModelConstants::ATTRIBUTE_TOPIC
        )
        ->namespace(BpmnModelConstants::NS)
        ->build();

        self::$typeAttribute = $typeBuilder->stringAttribute(
            BpmnModelConstants::ATTRIBUTE_TYPE
        )
        ->namespace(BpmnModelConstants::NS)
        ->build();

        self::$taskPriorityAttribute = $typeBuilder->stringAttribute(
            BpmnModelConstants::ATTRIBUTE_TASK_PRIORITY
        )
        ->namespace(BpmnModelConstants::NS)
        ->build();

        $typeBuilder->build();
    }

    public function builder(): ServiceTaskBuilder
    {
        return new ServiceTaskBuilder($this->modelInstance, $this);
    }

    public function getImplementation(): string
    {
        return self::$implementationAttribute->getValue($this);
    }

    public function setImplementation(string $implementation): void
    {
        self::$implementationAttribute->setValue($this, $implementation);
    }

    public function getOperation(): OperationInterface
    {
        return self::$operationRefChild->getReferenceTargetElement($this);
    }

    public function setOperation(OperationInterface $operation): void
    {
        self::$operationRefChild->setReferenceTargetElement($this, $operation);
    }

    public function getClass(): string
    {
        return self::$classAttribute->getValue($this);
    }

    public function setClass(string $class): void
    {
        self::$classAttribute->setValue($this, $class);
    }

    public function getExpression(): string
    {
        return self::$expressionAttribute->getValue($this);
    }

    public function setExpression(string $expression): void
    {
        self::$expressionAttribute->setValue($this, $expression);
    }

    public function getDelegateExpression(): string
    {
        return self::$delegateExpressionAttribute->getValue($this);
    }

    public function setDelegateExpression(string $expression): void
    {
        self::$delegateExpressionAttribute->setValue($this, $expression);
    }

    public function getResultVariable(): string
    {
        return self::$resultVariableAttribute->getValue($this);
    }

    public function setResultVariable(string $resultVariable): void
    {
        self::$resultVariableAttribute->setValue($this, $resultVariable);
    }

    public function getTopic(): string
    {
        return self::$topicAttribute->getValue($this);
    }

    public function setTopic(string $topic): void
    {
        self::$topicAttribute->setValue($this, $topic);
    }

    public function getType(): string
    {
        return self::$typeAttribute->getValue($this);
    }

    public function setType(string $type): void
    {
        self::$typeAttribute->setValue($this, $type);
    }

    public function getTaskPriority(): string
    {
        return self::$taskPriorityAttribute->getValue($this);
    }

    public function setTaskPriority(string $taskPriority): void
    {
        self::$taskPriorityAttribute->setValue($this, $taskPriority);
    }
}
