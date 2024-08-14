<?php

declare(strict_types=1);

namespace NW\WebService\References\Operations\Notification;

class TsReturnOperationResult
{
    public bool $notificationEmployeeByEmail = false;
    public bool $notificationClientByEmail = false;
    public bool $notificationClientBySms = false;
    public string $notificationClientBySmsMessage = '';

    public function toArray(): array
    {
        return [
            'notificationEmployeeByEmail' => $this->notificationEmployeeByEmail,
            'notificationClientByEmail'   => $this->notificationClientByEmail,
            'notificationClientBySms'     => [
                'isSent'  => $this->notificationClientBySms,
                'message' => $this->notificationClientBySmsMessage,
            ],
        ];
    }
}
