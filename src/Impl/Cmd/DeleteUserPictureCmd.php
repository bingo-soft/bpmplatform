<?php

namespace Jabe\Impl\Cmd;

use Jabe\Impl\Interceptor\{
    CommandInterface,
    CommandContext
};
use Jabe\Impl\Util\EnsureUtil;

class DeleteUserPictureCmd implements CommandInterface
{
    protected $userId;

    public function __construct(?string $userId)
    {
        $this->userId = $userId;
    }

    public function execute(CommandContext $commandContext, ...$args)
    {
        EnsureUtil::ensureNotNull("UserId", "UserId", $this->userId);

        $infoEntity = $commandContext->getIdentityInfoManager()
            ->findUserInfoByUserIdAndKey($this->userId, "picture");

        if ($infoEntity !== null) {
            $byteArrayId = $infoEntity->getValue();
            if ($byteArrayId !== null) {
                $commandContext->getByteArrayManager()
                    ->deleteByteArrayById($byteArrayId);
            }
            $commandContext->getIdentityInfoManager()
            ->delete($infoEntity);
        }

        return null;
    }

    public function isRetryable(): bool
    {
        return false;
    }
}
