<?php

namespace NW\WebService\References\Operations\Notification;

class TemplateManager
{
    /**
     * @throws \Exception
     */
    public function getTemplateData(array $data, array $entities): array
    {
        $notificationType = (int) $data['notificationType'];
        $differences = '';

        if ($notificationType === TsReturnOperation::TYPE_NEW) {
            $differences = __('NewPositionAdded', null, $data['resellerId']);
        } elseif ($notificationType === TsReturnOperation::TYPE_CHANGE && !empty($data['differences'])) {
            $differences = __('PositionStatusHasChanged', [
                'FROM' => Status::getName((int) $data['differences']['from']),
                'TO'   => Status::getName((int) $data['differences']['to']),
            ], $data['resellerId']);
        }

        $templateData = [
            'COMPLAINT_ID'       => (int) $data['complaintId'],
            'COMPLAINT_NUMBER'   => (string) $data['complaintNumber'],
            'CREATOR_ID'         => (int) $data['creatorId'],
            'CREATOR_NAME'       => $entities['creator']->getFullName(),
            'EXPERT_ID'          => (int) $data['expertId'],
            'EXPERT_NAME'        => $entities['expert']->getFullName(),
            'CLIENT_ID'          => (int) $data['clientId'],
            'CLIENT_NAME'        => $entities['client']->getFullName() ?: $entities['client']->name,
            'CONSUMPTION_ID'     => (int) $data['consumptionId'],
            'CONSUMPTION_NUMBER' => (string) $data['consumptionNumber'],
            'AGREEMENT_NUMBER'   => (string) $data['agreementNumber'],
            'DATE'               => (string) $data['date'],
            'DIFFERENCES'        => $differences,
        ];

        foreach ($templateData as $key => $tempData) {
            if (empty($tempData)) {
                throw new \Exception("Template Data ({$key}) is empty!", 500);
            }
        }

        return $templateData;
    }
}
