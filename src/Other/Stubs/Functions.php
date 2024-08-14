<?php

declare(strict_types=1);

namespace Other\Stubs;

function getResellerEmailFrom()
{
    return 'contractor@example.com';
}

function getEmailsByPermit($resellerId, $event)
{
    // fakes the method
    return ['someemeil@example.com', 'someemeil2@example.com'];
}

function __(string $element, array|null $data, int $resellerId): string
{
    return $element . ',<data>,' . $resellerId;
    // return sprintf('%s,%s,%d', $element, print_r($data, true), $resellerId);
}
