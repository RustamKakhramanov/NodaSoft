<?php

namespace NW\WebService\References\Operations\Notification;

class NotificationSender
{
    public function sendNotifications(array $data, array $entities, array $templateData, ResultDTO $result): ResultDTO
    {
        $resellerId = (int) $data['resellerId'];
        $notificationType = (int) $data['notificationType'];
        $emailFrom = getResellerEmailFrom($resellerId);

        // Отправка email сотрудникам
        $emails = getEmailsByPermit($resellerId, 'tsGoodsReturn');
        if (!empty($emailFrom) && count($emails) > 0) {
            foreach ($emails as $email) {
                MessagesClient::sendMessage([
                    0 => [
                        'emailFrom' => $emailFrom,
                        'emailTo'   => $email,
                        'subject'   => __('complaintEmployeeEmailSubject', $templateData, $resellerId),
                        'message'   => __('complaintEmployeeEmailBody', $templateData, $resellerId),
                    ],
                ], $resellerId, NotificationEvents::CHANGE_RETURN_STATUS);
                $result->notificationEmployeeByEmail = true;
            }
        }

        // Отправка уведомлений клиенту при смене статуса
        if ($notificationType === TsReturnOperation::TYPE_CHANGE && !empty($data['differences']['to'])) {
            if (!empty($emailFrom) && !empty($entities['client']->email)) {
                MessagesClient::sendMessage([
                    0 => [
                        'emailFrom' => $emailFrom,
                        'emailTo'   => $entities['client']->email,
                        'subject'   => __('complaintClientEmailSubject', $templateData, $resellerId),
                        'message'   => __('complaintClientEmailBody', $templateData, $resellerId),
                    ],
                ], $resellerId, $entities['client']->id, NotificationEvents::CHANGE_RETURN_STATUS, (int) $data['differences']['to']);

                $result->notificationClientByEmail = true;
            }

            if (!empty($entities['client']->mobile)) {
                $res = NotificationManager::send($resellerId, $entities['client']->id, NotificationEvents::CHANGE_RETURN_STATUS, (int) $data['differences']['to'], $templateData, $error);
                if ($res) {
                    $result->notificationClientBySms['isSent'] = true;
                }
                if (!empty($error)) {
                    $result->notificationClientBySms['message'] = $error;
                }
            }
        }

        return $result;
    }
}
