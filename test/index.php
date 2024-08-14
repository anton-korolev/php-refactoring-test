<?php

declare(strict_types=1);

namespace NW\WebService\References\Operations\Notification;

use Other\Stubs\MessagesClient;
use Other\Stubs\NotificationManager;

$loader = require __DIR__ . '/../vendor/autoload.php';

require_once(__DIR__ . '/../Src/Other/Stubs/Functions.php');

function testOperation(array $data): void
{
    $request = new TsReturnOperationRequest($data);
    if (!$request->hasErrors()) {
        $operation = new TsReturnOperation(
            $request->parameters,
            new MessagesClient(),
            new NotificationManager()
        );
        $result = $operation->doOperation();
        echo "Operation result:\n";
        var_dump($result);
    } else {
        var_dump($request->errors());
    }
}

// Valid request data for a new return operation
$data = [
    'notificationType' => '1', // see `NotificationEvent` for a list of valid values
    'complaintId' => '1',
    'complaintNumber' => '000001',
    'resellerId' => '1',
    'creatorId' => '2',
    'expertId' => '2',
    'clientId' => '3',
    'consumptionId' => '1',
    'consumptionNumber' => '000002',
    'agreementNumber' => '000003',
    'date' => '13.08.2024',
    'oldStatus' => '1', // see `OperationStatus` for a list of valid values
    'newStatus' => '1', // see `OperationStatus` for a list of valid values
];

echo "======= Validation errors test: =======\n";
testOperation([]);
echo "======= Test a new return operation: =======\n";
testOperation($data);
echo "======= Test the changes to the return operation: =======\n";
$data['notificationType'] = 2;
$data['newStatus'] = 2;
testOperation($data);
