<?php

declare(strict_types=1);

namespace Other\Stubs;

class NotificationManager
{
    public function send(int $resellerId, int $cliendId, string $event, int $newStatus, array $templateData, string &$error): bool
    {
        printf("NotificationManager::sendMessage => {%d,%d,%s,%d}\n\n", $resellerId, $cliendId, $event, $newStatus);
        $error = '';
        return true;
    }
}
