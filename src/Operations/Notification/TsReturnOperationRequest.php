<?php

declare(strict_types=1);

namespace NW\WebService\References\Operations\Notification;

use Other\Entities\Client;
use Other\Entities\Employee;
use Other\Entities\Seller;
use Other\OperationStatus;

/**
 * Example of a `TsReturnOperationParameters` preparation class for calling `TsReturnOperation`.
 */
class TsReturnOperationRequest
{
    /** @var array<string,<int,string>> list of validation errors (attribute => [code => message]) */
    private array $errorList = [];

    /** @var TsReturnOperationParameters parameters for calling `TsReturnOperation`. */
    public TsReturnOperationParameters $parameters;

    /**
     * Validates the input data and creates an instance of `TsReturnOperationParameters` from it to
     * call `TsReturnOperation` if the validation passed without errors.
     *
     * Use the `errors()` method to get validation errors.
     *
     * Expected structure of the input array `$data`:
     * ```php
     * $data = [
     *     'notificationType' => (Int), // see `NotificationEvent` for a list of valid values
     *     'complaintId' => (Int),
     *     'complaintNumber' => (String),
     *     'resellerId' => (Int),
     *     'creatorId' => (Int),
     *     'expertId' => (Int),
     *     'clientId' => (Int),
     *     'consumptionId' => (Int),
     *     'consumptionNumber' => (String),
     *     'agreementNumber' => (String),
     *     'date' => (String),
     *     'oldStatus' => (Int), // see `OperationStatus` for a list of valid values
     *     'newStatus' => (Int), // see `OperationStatus` for a list of valid values
     * ]
     * ```
     */
    public function __construct(array $data)
    {
        $notificationType = $this->toNotificationEvent(
            $data['notificationType'] ?? null,
            'notificationType',
            400,
            'Invalid notificationType.'
        );
        $complaintId = $this->toInt($data['complaintId'] ?? null, 'complaintId', 400, 'Invalid complaintId.');
        $complaintNumber = $this->toString($data['complaintNumber'] ?? null, 'complaintNumber', 400, 'Invalid complaintNumber.');
        $resellerId = $this->toInt($data['resellerId'] ?? null, 'resellerId', 400, 'Invalid resellerId.');
        $creatorId = $this->toInt($data['creatorId'] ?? null, 'creatorId', 400, 'Invalid creatorId.');
        $expertId = $this->toInt($data['expertId'] ?? null, 'expertId', 400, 'Invalid expertId.');
        $clientId = $this->toInt($data['clientId'] ?? null, 'clientId', 400, 'Invalid clientId.');
        $consumptionId = $this->toInt($data['consumptionId'] ?? null, 'consumptionId', 400, 'Invalid consumptionId.');
        $consumptionNumber = $this->toString($data['consumptionNumber'] ?? null, 'consumptionNumber', 400, 'Invalid consumptionNumber.');
        $agreementNumber = $this->toString($data['agreementNumber'] ?? null, 'agreementNumber', 400, 'Invalid agreementNumber.');
        $date = $this->toString($data['date'] ?? null, 'date', 400, 'Invalid date.');
        $oldStatus = $this->toOperationStatus($data['oldStatus'] ?? null, 'oldStatus', 400, 'Invalid oldStatus.');
        $newStatus = $this->toOperationStatus($data['newStatus'] ?? null, 'newStatus', 400, 'Invalid newStatus.');

        if ($this->hasErrors()) {
            return;
        }

        $reseller = Seller::getById($resellerId);
        if ($reseller === null) {
            // throw new \Exception('Seller not found!', 400);
            $this->addError('resellerId', 400, 'Seller not found.');
        }

        $creator = Employee::getById($creatorId);
        if ($creator === null) {
            // throw new \Exception('Creator not found!', 400);
            $this->addError('creatorId', 400, 'Creator not found.');
        } else {
            $creatorName = $creator->name;
        }

        $expert = Employee::getById($expertId);
        if ($expert === null) {
            // throw new \Exception('Expert not found!', 400);
            $this->addError('expertId', 400, 'Expert not found.');
        } else {
            $expertName = $expert->name;
        }

        $client = Client::getById($clientId);
        if ($client === null /* || $client->type !== Contractor::TYPE_CUSTOMER */) {
            // throw new \Exception('Client not found!', 400);
            $this->addError('clientId', 400, 'Client not found.');
        } elseif ($client->seller->id !== $resellerId) {
            $this->addError('clientId', 400, 'Seller at Client is inconsistent.');
        } else {
            $clientName = $client->getFullName();
            $clientEmail = $client->email;
            $clientMobile = $client->mobile;
        }

        if (!$this->hasErrors()) {
            $this->parameters = new TsReturnOperationParameters(
                $notificationType,
                $complaintId,
                $complaintNumber,
                $resellerId,
                $creatorId,
                $creatorName,
                $expertId,
                $expertName,
                $clientId,
                $clientName,
                $clientEmail,
                $clientMobile,
                $consumptionId,
                $consumptionNumber,
                $agreementNumber,
                $date,
                $oldStatus,
                $newStatus,
            );
        }
    }

    /**
     * Adds an error to the validation error list (see `$errorList`).
     *
     * The previous error for the passed attribute will be replaced.
     *
     * @param string $attribute attribute name.
     * @param int $code error code.
     * @param string $message error message.
     * @return void
     */
    private function addError(string $attribute, int $code, string $message): void
    {
        $this->errorList[$attribute] = [$code => $message];
    }

    /**
     * @return bool
     */
    public function hasErrors(): bool
    {
        return !empty($this->errorList);
    }

    /**
     * @return bool
     */
    public function errors(): array
    {
        return $this->errorList;
    }

    /**
     * Converts the passed value to the `NotificationEvent` type.
     *
     * If it fails, the conversion error will be stored with the passed error code and message
     * (see `addError()`).
     *
     * @param mixed $value value to convert. An integer is expected.
     * @param string $attribute attribute name.
     * @param int $errorCode error code.
     * @param string $errorMessage error message.
     * @return NotificationEvent|null converted value, or null on failure.
     */
    private function toNotificationEvent(
        mixed $value,
        string $attribute,
        int $errorCode,
        string $errorMessage
    ): NotificationEvent|null {
        $result = NotificationEvent::tryFromIndex($value);
        if (is_null($result)) {
            $this->addError($attribute, $errorCode, $errorMessage);
        }
        return $result;
    }

    /**
     * Converts the passed value to `Int`.
     *
     * If it fails, the conversion error will be stored with the passed error code and message
     * (see `addError()`).
     *
     * @param mixed $value value to convert.
     * @param string $attribute attribute name.
     * @param int $errorCode error code.
     * @param string $errorMessage error message.
     * @return int|null converted value, or null on failure.
     */
    private function toInt(
        mixed $value,
        string $attribute,
        int $errorCode,
        string $errorMessage
    ): int|null {
        $result = is_numeric($value) ? (int)$value : null;
        if (is_null($result)) {
            $this->addError($attribute, $errorCode, $errorMessage);
        }
        return $result;
    }

    /**
     * Converts the passed value to `String`.
     *
     * If it fails, the conversion error will be stored with the passed error code and message
     * (see `addError()`).
     *
     * @param mixed $value value to convert.
     * @param string $attribute attribute name.
     * @param int $errorCode error code.
     * @param string $errorMessage error message.
     * @return string|null converted value, or null on failure.
     */
    private function toString(
        mixed $value,
        string $attribute,
        int $errorCode,
        string $errorMessage
    ): string|null {
        $result = is_string($value) ? (string)$value : null;
        if (empty($result)) {
            $this->addError($attribute, $errorCode, $errorMessage);
        }
        return $result;
    }

    /**
     * Converts the passed value to the `OperationStatus` type.
     *
     * If it fails, the conversion error will be stored with the passed error code and message
     * (see `addError()`).
     *
     * @param mixed $value value to convert. An integer is expected (see `OperationStatus`).
     * @param string $attribute attribute name.
     * @param int $errorCode error code.
     * @param string $errorMessage error message.
     * @return OperationStatus|null converted value, or null on failure.
     */
    private function toOperationStatus(
        mixed $value,
        string $attribute,
        int $errorCode,
        string $errorMessage
    ): OperationStatus|null {
        $result = OperationStatus::tryFromMixed($value);
        if (is_null($result)) {
            $this->addError($attribute, $errorCode, $errorMessage);
        }
        return $result;
    }
}
