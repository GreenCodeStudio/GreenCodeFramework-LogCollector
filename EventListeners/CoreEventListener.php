<?php

namespace LogCollector\EventListeners;

class CoreEventListener
{
    public function standardControllerRouted($data)
    {
        if (!empty($_ENV['greenlogcollector_url'])) {
            $curl = curl_init();
            $payload = json_encode([
                'projectKey' => $_ENV['greenlogcollector_key'],
                'userIdentifier' => $_COOKIE['id'] ?? '',
                'userAgent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'ipAddress' => $_SERVER['REMOTE_ADDR'] ?? '',
                'url' => ($_SERVER['REQUEST_SCHEME'] ?? 'http').'://'.($_SERVER['HTTP_HOST'] ?? '(unknown)').($_SERVER['REQUEST_URI'] ?? ''),
                'created' => date('Y-m-d H:i:s'),
//                'pageOpenIdentifier' => $pageOpenIdentifier,
            ]);

            curl_setopt_array($curl, [
                CURLOPT_URL => rtrim($_ENV['greenlogcollector_url'],'/').'/api/VisitLog',
                CURLOPT_POST => true,
                CURLOPT_POSTFIELDS => $payload,
                CURLOPT_HTTPHEADER => [
                    'Content-Type: application/json',
                    'Content-Length: '.strlen($payload),
                ],
                CURLOPT_RETURNTRANSFER => true,
            ]);

            curl_exec($curl);
        }
    }
}
