<?php

declare(strict_types=1);

namespace NW\WebService\References\Operations\Notification;

use Other\OperationInterface;
use Other\Stubs\MessagesClient;
use Other\Stubs\NotificationManager;

use function Other\Stubs\getEmailsByPermit;
use function Other\Stubs\getResellerEmailFrom;
use function Other\Stubs\__;

/**
 * Notification operation.
 *
 * Sends notifications aboute return operation.
 */
class TsReturnOperation implements OperationInterface
{
    /**
     */
    public function __construct(
        protected readonly TsReturnOperationParameters $parameters,
        protected readonly MessagesClient $messagesClient,
        protected readonly NotificationManager $notificationManager,
    ) {}

    /**
     * Returns a string representation of status differences.
     *
     * @return string string representation of status differences.
     */
    protected function getDifferences(): string
    {
        return match ($this->parameters->notificationType) {
            NotificationEvent::new => __(
                'NewPositionAdded',
                null,
                $this->parameters->resellerId
            ),
            default => __(
                'PositionStatusHasChanged',
                [
                    'FROM' => $this->parameters->oldStatus->name,
                    'TO'   => $this->parameters->newStatus->name,
                ],
                $this->parameters->resellerId
            ),
        };
    }

    /**
     * Returns template data for the notification.
     *
     * @return array template data.
     */
    protected function getTemplateData(): array
    {
        return [
            'COMPLAINT_ID'       => $this->parameters->complaintId,
            'COMPLAINT_NUMBER'   => $this->parameters->complaintNumber,
            'CREATOR_ID'         => $this->parameters->creatorId,
            'CREATOR_NAME'       => $this->parameters->creatorName,
            'EXPERT_ID'          => $this->parameters->expertId,
            'EXPERT_NAME'        => $this->parameters->expertName,
            'CLIENT_ID'          => $this->parameters->clientId,
            'CLIENT_NAME'        => $this->parameters->clientName,
            'CONSUMPTION_ID'     => $this->parameters->consumptionId,
            'CONSUMPTION_NUMBER' => $this->parameters->consumptionNumber,
            'AGREEMENT_NUMBER'   => $this->parameters->agreementNumber,
            'DATE'               => $this->parameters->date,
            'DIFFERENCES'        => $this->getDifferences(),
        ];
    }

    /**
     * Executes operation.
     *
     * @return array
     */
    public function doOperation(): array
    {
        $result = new TsReturnOperationResult();

        $templateData = $this->getTemplateData();

        $emailFrom = getResellerEmailFrom($this->parameters->resellerId);
        // Получаем email сотрудников из настроек
        $emails = getEmailsByPermit($this->parameters->resellerId, 'tsGoodsReturn');
        if (!empty($emailFrom) && count($emails) > 0) {
            foreach ($emails as $email) {
                $this->messagesClient->sendMessage(
                    [
                        0 => [ // MessageTypes::EMAIL
                            'emailFrom' => $emailFrom,
                            'emailTo'   => $email,
                            'subject'   => __('complaintEmployeeEmailSubject', $templateData, $this->parameters->resellerId),
                            'message'   => __('complaintEmployeeEmailBody', $templateData, $this->parameters->resellerId),
                        ],
                    ],
                    $this->parameters->resellerId,
                    $this->parameters->clientId,
                    $this->parameters->notificationType->value,
                    $this->parameters->newStatus->value
                );
                $result->notificationEmployeeByEmail = true;
            }
        }


        // Шлём клиентское уведомление, только если произошла смена статуса
        if ($this->parameters->notificationType !== NotificationEvent::new) {
            if (!empty($emailFrom) && !empty($this->parameters->clientEmail)) {
                $this->messagesClient->sendMessage(
                    [
                        0 => [ // MessageTypes::EMAIL
                            'emailFrom' => $emailFrom,
                            'emailTo'   => $this->parameters->clientEmail,
                            'subject'   => __('complaintClientEmailSubject', $templateData, $this->parameters->resellerId),
                            'message'   => __('complaintClientEmailBody', $templateData, $this->parameters->resellerId),
                        ],
                    ],
                    $this->parameters->resellerId,
                    $this->parameters->clientId,
                    $this->parameters->notificationType->value,
                    $this->parameters->newStatus->value
                );
                $result->notificationClientByEmail = true;
            }

            if (!empty($this->parameters->clientMobile)) {
                $error = '';
                $result->notificationClientBySms = $this->notificationManager->send(
                    $this->parameters->resellerId,
                    $this->parameters->clientId,
                    $this->parameters->notificationType->value,
                    $this->parameters->newStatus->value,
                    $templateData,
                    $error
                );
                if (!empty($error)) {
                    $result->notificationClientBySmsMessage = $error;
                }
            }
        }

        return $result->toArray();
    }
}
