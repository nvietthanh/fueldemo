<?php

namespace Traits;

use Fuel\Core\Response;
use SimpleXMLElement;

trait ResponseTrait
{
    /**
     * Return a standardized JSON response.
     * 
     * @return Response
     */
    public static function json_response(?array $data = [], string $message = 'Success', int $status_code = 200, array $headers = [])
    {
        $body = [
            'status'  => $status_code,
            'message' => $message,
            'data'    => $data,
        ];

        return Response::forge(
            json_encode($body, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT),
            $status_code,
            $headers
        )->set_header('Content-Type', 'application/json');
    }

    /**
     * Return a standardized XML response.
     * 
     * @return Response
     */
    public static function xml_response(?array $data = [], string $message = 'Success', int $status_code = 200, array $headers = [])
    {
        $response_data = [
            'status'  => $status_code,
            'message' => $message,
            'data'    => $data,
        ];

        $xml = new SimpleXMLElement('<response/>');
        self::array_to_xml($response_data, $xml);

        return Response::forge(
            $xml->asXML(),
            $status_code,
            $headers
        )->set_header('Content-Type', 'application/xml');
    }

    /**
     * Convert array to XML.
     */
    private function array_to_xml(array $data, SimpleXMLElement &$xml)
    {
        foreach ($data as $key => $value) {
            $key = is_numeric($key) ? "item{$key}" : $key;
            if (is_array($value)) {
                $subnode = $xml->addChild($key);
                $this->array_to_xml($value, $subnode);
            } else {
                $xml->addChild($key, htmlspecialchars((string)$value));
            }
        }
    }
}
