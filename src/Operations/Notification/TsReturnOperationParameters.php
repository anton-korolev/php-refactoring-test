<?php

declare(strict_types=1);

namespace NW\WebService\References\Operations\Notification;

use Other\OperationStatus;

// use stdClass;

/**
 * Input parameters for `TsReturnOperation`.
 */
class TsReturnOperationParameters
{
    public readonly array $s;

    /**
     * @param NotificationEvent $notificationType
     * @param int $complaintId
     * @param string $complaintNumber
     * @param int $resellerId
     * @param int $creatorId
     * @param string $creatorName
     * @param int $expertId
     * @param string $expertName
     * @param int $clientId
     * @param string $clientName
     * @param string $clientEmail
     * @param string $clientMobile
     * @param int $consumptionId
     * @param string $consumptionNumber
     * @param string $agreementNumber
     * @param string $date
     * @param OperationStatus $oldStatus
     * @param OperationStatus $newStatus
     *
     * @throws \Exception on incorrect statuses.
     */
    public function __construct(
        public readonly NotificationEvent $notificationType,
        public readonly int $complaintId,
        public readonly string $complaintNumber,
        public readonly int $resellerId,
        public readonly int $creatorId,
        public readonly string $creatorName,
        public readonly int $expertId,
        public readonly string $expertName,
        public readonly int $clientId,
        public readonly string $clientName,
        public readonly string $clientEmail,
        public readonly string $clientMobile,
        public readonly int $consumptionId,
        public readonly string $consumptionNumber,
        public readonly string $agreementNumber,
        public readonly string $date,
        public readonly OperationStatus $oldStatus,
        public readonly OperationStatus $newStatus,

    ) {
        // Specific validation of parameters
        if (
            $notificationType !== NotificationEvent::new
            && $oldStatus === $newStatus
        ) {
            throw new \Exception('Status has not changed!', 400);
        }
    }
}
