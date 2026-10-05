<?php

namespace LogCollector\Ajax;

use Core\AjaxController;

class FrontendLogCollectorAjax extends AjaxController
{
    public function logJsInit($pageOpenIdentifier)
    {
        if (!empty($_ENV['greenlogcollector_url'])) {
            $curl = curl_init();
            $payload = json_encode([
                'projectKey' => $_ENV['greenlogcollector_key'],
                'source' => 'browser',
                'type' => 'jsInit',
                'pageOpenIdentifier' => $pageOpenIdentifier,
                'created' => date('Y-m-d H:i:s'),
            ]);

            curl_setopt_array($curl, [
                CURLOPT_URL => rtrim($_ENV['greenlogcollector_url'], '/').'/api/ExtraLog',
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
    public function hasPermission(string $methodName)
    {
        return true;
    }
}
