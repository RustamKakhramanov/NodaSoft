<?php

namespace NW\WebService\References\Operations\Notification;


class TsReturnOperation extends ReferencesOperation
{
    public const TYPE_NEW    = 1;
    public const TYPE_CHANGE = 2;

    /**
     * @throws \Exception
     */
    public function doOperation(): array
    {
        $data = (array) $this->getRequest('data');
        $resultStructure = new ResultDTO;

        // Валидация данных запроса
        $validator = new RequestValidator();
        $validator->validate($data);

        // Получение сущностей
        $entityManager = new EntityManager();
        $entities = $entityManager->getEntities($data);

        // Формирование данных шаблона
        $templateManager = new TemplateManager();
        $templateData = $templateManager->getTemplateData($data, $entities);

        // Отправка уведомлений
        $notificationSender = new NotificationSender();
        $result = $notificationSender->sendNotifications($data, $entities, $templateData, $resultStructure);

        return $result->toArray();
    }
}