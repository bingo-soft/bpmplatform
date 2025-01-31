<?php

namespace Jabe\Impl\Cmd;

use Jabe\Impl\Interceptor\{
    CommandInterface,
    CommandContext
};
use Jabe\Impl\Util\EnsureUtil;

class CreateMembershipCmd extends AbstractWritableIdentityServiceCmd implements CommandInterface
{
    private $userId;
    private $groupId;

    public function __construct(?string $userId, ?string $groupId)
    {
        $this->userId = $userId;
        $this->groupId = $groupId;
    }

    public function __serialize(): array
    {
        return [
            'userId' => $this->userId,
            'groupId' => $this->groupId
        ];
    }

    public function __unserialize(array $data): void
    {
        $this->userId = $data['userId'];
        $this->groupId = $data['groupId'];
    }

    protected function executeCmd(CommandContext $commandContext)
    {
        EnsureUtil::ensureNotNull("userId", "userId", $this->userId);
        EnsureUtil::ensureNotNull("groupId", "groupId", $this->groupId);

        $operationResult = $commandContext
            ->getWritableIdentityProvider()
            ->createMembership($this->userId, $this->groupId);

        $commandContext->getOperationLogManager()->logMembershipOperation($operationResult, $this->userId, $this->groupId, null);

        return null;
    }
}
