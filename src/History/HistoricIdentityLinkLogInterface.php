<?php

namespace Jabe\History;

interface HistoricIdentityLinkLogInterface
{
    /**
     * Returns the id of historic identity link (Candidate or Assignee or Owner).
     */
    public function getId(): ?string;
    /**
     * Returns the type of link (Candidate or Assignee or Owner).
     * See IdentityLinkType for the native supported types by the process engine.
     *
     * */
    public function getType(): ?string;

    /**
     * If the identity link involves a user, then this will be a non-null id of a user.
     * That userId can be used to query for user information through the UserQuery API.
     */
    public function getUserId(): ?string;

    /**
     * If the identity link involves a group, then this will be a non-null id of a group.
     * That groupId can be used to query for user information through the GroupQuery API.
     */
    public function getGroupId(): ?string;

    /**
     * The id of the task associated with this identity link.
     */
    public function getTaskId(): ?string;

    /**
     * Returns the userId of the user who assigns a task to the user
     */
    public function getAssignerId();

    /**
     * Returns the type of identity link history (add or delete identity link)
     */
    public function getOperationType(): ?string;

    /**
     * Returns the time of identity link event (Creation/Deletion)
     */
    public function getTime(): ?string;

    /**
     * Returns the id of the related process definition
     */
    public function getProcessDefinitionId(): ?string;

    /**
     * Returns the key of the related process definition
     */
    public function getProcessDefinitionKey(): ?string;

    /**
     * Returns the id of the related tenant
     */
    public function getTenantId(): ?string;

    /**
     * Returns the root process instance id of
     * the related process instance
     */
    public function getRootProcessInstanceId(): ?string;

    /** The time the historic identity link log will be removed. */
    public function getRemovalTime(): ?string;
}
