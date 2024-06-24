<?php
namespace NW\WebService\References\Operations\Notification;

class EntityManager
{
    /**
     * @throws \Exception
     */
    public static function getEntities(array $data): array
    {
        $resellerId = (int) $data['resellerId'];
        $entities = [];

        $entities['reseller'] = Seller::getById($resellerId);
        if ($entities['reseller'] === null) {
            throw new \Exception('Seller not found!', 400);
        }

        $entities['client'] = Contractor::getById((int) $data['clientId']);
        if ($entities['client'] === null || $entities['client']->type !== Contractor::TYPE_CUSTOMER || $entities['client']->Seller->id !== $resellerId) {
            throw new \Exception('Client not found!', 400);
        }

        $entities['creator'] = Employee::getById((int) $data['creatorId']);
        if ($entities['creator'] === null) {
            throw new \Exception('Creator not found!', 400);
        }

        $entities['expert'] = Employee::getById((int) $data['expertId']);
        if ($entities['expert'] === null) {
            throw new \Exception('Expert not found!', 400);
        }

        return $entities;
    }

}
