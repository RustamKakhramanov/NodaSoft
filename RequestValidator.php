<?php

namespace NW\WebService\References\Operations\Notification;

class RequestValidator
{
    /**
     * @throws \Exception
     */
    public function validate(array $data): void
    {
        if (empty((int) $data['resellerId'])) {
            throw new \Exception('Empty resellerId', 400);
        }

        if (empty((int) $data['notificationType'])) {
            throw new \Exception('Empty notificationType', 400);
        }
    }
}
