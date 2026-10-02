<?php

namespace LogCollector\EventListeners;

use Core\AbstractEventListener;

class CoreEventListener extends AbstractEventListener
{
    public function routeInvoked($data)
    {
        if ($data->controllerType == 'Controllers') {
            if (!empty($_ENV['greenlogcollector_url'])) {
                $curl = curl_init();
                $payload = json_encode([
                    'projectKey' => $_ENV['greenlogcollector_key'],
//                'userIdentifier' => $_COOKIE['id'] ?? '',
                    'userAgent' => $data->userAgent,
                    'ipAddress' => $data->ipAddress,
                    'url' => $data->url,
                    'created' => $this->created->format('Y-m-d H:i:s'),
//                'pageOpenIdentifier' => $pageOpenIdentifier,
                ]);

                curl_setopt_array($curl, [
                    CURLOPT_URL => rtrim($_ENV['greenlogcollector_url'], '/').'/api/VisitLog',
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $payload,
                    CURLOPT_HTTPHEADER => [
                        'Content-Type: application/json',
                        'Content-Length: '.strlen($payload),
                    ],
                    CURLOPT_RETURNTRANSFER => true,
                ]);
                curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
                curl_exec($curl);
                dump(curl_error($curl));
            }
        }
    }
}
