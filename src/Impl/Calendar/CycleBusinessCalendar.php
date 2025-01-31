<?php

namespace Jabe\Impl\Calendar;

use Cron\CronExpression;
use Jabe\Impl\ProcessEngineLogger;
use Jabe\Impl\Util\{
    ClockUtil,
    EngineUtilLogger
};

class CycleBusinessCalendar implements BusinessCalendarInterface
{
    //private final static EngineUtilLogger LOG = ProcessEngineLogger.UTIL_LOGGER;

    public const NAME = "cycle";

    public function resolveDuedate(?string $duedateDescription, $startDate = null, int $repeatOffset = 0, ...$args): ?\DateTime
    {
        try {
            if (strpos($duedateDescription, "R") === 0) {
                $durationHelper = new DurationHelper($duedateDescription, $startDate);
                $durationHelper->setRepeatOffset($repeatOffset);
                return $durationHelper->getDateAfter($startDate);
            } else {
                $cron = new CronExpression($duedateDescription);
                return $cron->getNextRunDate($startDate ?? ClockUtil::getCurrentTime(...$args));
            }
        } catch (\Throwable $e) {
            //throw LOG.exceptionWhileParsingCronExpresison(duedateDescription, e);
            throw $e;
        }
    }
}
