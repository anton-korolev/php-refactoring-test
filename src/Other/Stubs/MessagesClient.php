<?php

declare(strict_types=1);

namespace Other\Stubs;

class MessagesClient
{
    public function sendMessage(array $message, int $resellerId, int $clientId, string $event, int $newstatus): bool
    {
        printf("MessagesClient::sendMessage => {%s,%d,%d,%s,%d}\n\n", print_r($message, true), $resellerId, $clientId, $event, $newstatus);
        return true;
    }
}
