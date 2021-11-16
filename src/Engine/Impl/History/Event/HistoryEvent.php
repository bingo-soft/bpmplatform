<?php

namespace BpmPlatform\Engine\Impl\History\Event;

use BpmPlatform\Engine\Impl\Db\{
    DbEntityInterface,
    HistoricEntityInterface
};

class HistoryEvent implements \Serializable, DbEntityInterface, HistoricEntityInterface
{
    public const IDENTITY_LINK_ADD = HistoryEventTypes::IDENTITY_LINK_ADD[1];

    public const IDENTITY_LINK_DELETE = HistoryEventTypes::IDENTITY_LINK_DELETE[1];

    /** each {@link HistoryEvent} has a unique id */
    protected $id;

    /** the root process instance in which the event has happened */
    protected $rootProcessInstanceId;

    /** the process instance in which the event has happened */
    protected $processInstanceId;

    /** the id of the execution in which the event has happened */
    protected $executionId;

    /** the id of the process definition */
    protected $processDefinitionId;

    /** the key of the process definition */
    protected $processDefinitionKey;

    /** the name of the process definition */
    protected $processDefinitionName;

    /** the version of the process definition */
    protected $processDefinitionVersion;

    /** the case instance in which the event has happened */
    protected $caseInstanceId;

    /** the id of the case execution in which the event has happened */
    protected $caseExecutionId;

    /** the id of the case definition */
    protected $caseDefinitionId;

    /** the key of the case definition */
    protected $caseDefinitionKey;

    /** the name of the case definition */
    protected $caseDefinitionName;

    /**
     * The type of the activity audit event.
     * @see HistoryEventType#getEventName()
     * */
    protected $eventType;

    protected $sequenceCounter;

    /* the time when the history event will be deleted */
    protected $removalTime;

    // getters / setters ///////////////////////////////////

    public function getProcessInstanceId(): string
    {
        return $this->processInstanceId;
    }

    public function setProcessInstanceId(string $processInstanceId): void
    {
        $this->processInstanceId = $processInstanceId;
    }

    public function getRootProcessInstanceId(): string
    {
        return $this->rootProcessInstanceId;
    }

    public function setRootProcessInstanceId(string $rootProcessInstanceId): void
    {
        $this->rootProcessInstanceId = $rootProcessInstanceId;
    }

    public function getExecutionId(): string
    {
        return $this->executionId;
    }

    public function setExecutionId(string $executionId): void
    {
        $this->executionId = $executionId;
    }

    public function getProcessDefinitionId(): string
    {
        return $this->processDefinitionId;
    }

    public function setProcessDefinitionId(string $processDefinitionId): void
    {
        $this->processDefinitionId = $processDefinitionId;
    }

    public function getProcessDefinitionKey(): string
    {
        return $this->processDefinitionKey;
    }

    public function setProcessDefinitionKey(string $processDefinitionKey): void
    {
        $this->processDefinitionKey = $processDefinitionKey;
    }

    public function getProcessDefinitionName(): string
    {
        return $this->processDefinitionName;
    }

    public function setProcessDefinitionName(string $processDefinitionName): void
    {
        $this->processDefinitionName = $processDefinitionName;
    }

    public function getProcessDefinitionVersion(): int
    {
        return $this->processDefinitionVersion;
    }

    public function setProcessDefinitionVersion(int $processDefinitionVersion): void
    {
        $this->processDefinitionVersion = $processDefinitionVersion;
    }

    public function getCaseDefinitionName(): string
    {
        return $this->caseDefinitionName;
    }

    public function setCaseDefinitionName(string $caseDefinitionName): void
    {
        $this->caseDefinitionName = $caseDefinitionName;
    }

    public function getCaseDefinitionKey(): string
    {
        return $this->caseDefinitionKey;
    }

    public function setCaseDefinitionKey(string $caseDefinitionKey): void
    {
        $this->caseDefinitionKey = $caseDefinitionKey;
    }

    public function getCaseDefinitionId(): string
    {
        return $this->caseDefinitionId;
    }

    public function setCaseDefinitionId(string $caseDefinitionId): void
    {
        $this->caseDefinitionId = $caseDefinitionId;
    }

    public function getCaseInstanceId(): string
    {
        return $this->caseInstanceId;
    }

    public function setCaseInstanceId(string $caseInstanceId): void
    {
        $this->caseInstanceId = $caseInstanceId;
    }

    public function getCaseExecutionId(): string
    {
        return $this->caseExecutionId;
    }

    public function setCaseExecutionId(string $caseExecutionId): void
    {
        $this->caseExecutionId = $caseExecutionId;
    }

    public function setId(string $id): void
    {
        $this->id = $id;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getEventType(): string
    {
        return $this->eventType;
    }

    public function setEventType(string $eventType): void
    {
        $this->eventType = $eventType;
    }

    public function getSequenceCounter(): int
    {
        return $this->sequenceCounter;
    }

    public function setSequenceCounter(int $sequenceCounter): void
    {
        $this->sequenceCounter = $sequenceCounter;
    }

    public function getRemovalTime(): string
    {
        return $this->removalTime;
    }

    public function setRemovalTime(string $removalTime): void
    {
        $this->removalTime = $removalTime;
    }

    // persistent object implementation ///////////////

    public function getPersistentState(): string
    {
        // events are immutable
        return HistoryEvent::class;
    }

    // state inspection

    public function isEventOfType(HistoryEventTypeInterface $type): bool
    {
        return $type->getEventName() == $this->eventType;
    }

    public function serialize()
    {
        return json_encode([
            'id' => $this->id,
            'eventType' => $this->eventType,
            'executionId' => $this->executionId,
            'processDefinitionId' => $this->processDefinitionId,
            'processInstanceId' => $this->processInstanceId,
            'rootProcessInstanceId' => $this->rootProcessInstanceId,
            'removalTime' => $this->removalTime
        ]);
    }

    public function unserialize($data)
    {
        $json = json_decode($data);
        $this->id = $json->id;
        $this->eventType = $json->eventType;
        $this->executionId = $json->executionId;
        $this->processDefinitionId = $json->processDefinitionId;
        $this->processInstanceId = $json->processInstanceId;
        $this->rootProcessInstanceId = $json->rootProcessInstanceId;
        $this->removalTime = $json->removalTime;
    }

    public function __toString()
    {
        return get_class($this)
            . "[id=" . $this->id
            . ", eventType=" . $this->eventType
            . ", executionId=" . $this->executionId
            . ", processDefinitionId=" . $this->processDefinitionId
            . ", processInstanceId=" . $this->processInstanceId
            . ", rootProcessInstanceId=" . $this->rootProcessInstanceId
            . ", removalTime=" . $this->removalTime
            . "]";
    }
}
