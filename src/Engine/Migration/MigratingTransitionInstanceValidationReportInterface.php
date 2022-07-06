<?php

namespace Jabe\Engine\Migration;

interface MigratingTransitionInstanceValidationReportInterface
{
    /**
     * @return string the id of the source scope of the migrating transition instance
     */
    public function getSourceScopeId(): string;

    /**
     * @return string the transition instance id of this report
     */
    public function getTransitionInstanceId(): string;

    /**
     * @return MigrationInstructionInterface the migration instruction that cannot be applied
     */
    public function getMigrationInstruction(): MigrationInstructionInterface;

    /**
     * @return bool - true if the reports contains failures, false otherwise
     */
    public function hasFailures(): bool;

    /**
     * @return array the list of failures
     */
    public function getFailures(): array;
}
