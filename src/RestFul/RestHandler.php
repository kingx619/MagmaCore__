<?php
/*
 * This file is part of the MagmaCore package.
 *
 * (c) Ricardo Miller <ricardomiller@lava-studio.co.uk>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace MagmaCore\RestFul;

use MagmaCore\RestFul\RestResponse;

class RestHandler
{

    protected string $contentType;
    protected array $data = [];
    protected $response;
    protected const DEFAULT_CONTENT = 'json';
    protected const CODE_404 = 404;
    protected const CODE_500 = 500;

    /**
     * Undocumented function
     *
     * @param array $data
     * @param string|null $contentType
     */
    public function __construct(array $data, ?string $contentType = self::DEFAULT_CONTENT)
    {
        $this->data = $data;
        $this->contentType = $contentType;
    }

    public function response()
    {
        if (empty($this->data)) {
            $code = self::CODE_404;
            $this->data = ['error' => 'No data found!'];
        } else {
            $code = self::CODE_404;
        }
        (new RestResponse())->setHttpHeaders($this->contentType, $code);
        if (strpos($this->contentType, 'json') !== false) {
            $this->response = $this->jsonEncodedData($this->data);
        } elseif (strpos($this->contentType, 'html') !== false) {
            $this->response = $this->htmlEncodedData($this->data);
        } elseif (strpos($this->contentType, 'xml') !== false) {
            $this->response = $this->xmlEncodedData($this->data);
        } else {
            $this->response = $this->data;
        }

        if (isset($this->response) && null !== $this->response || $this->response !='') {
            return $this->response;
        }
    }

    private function jsonEncodedData(array $data)
    {
        if (null !== $data) {
            $json = json_encode($data);
            if ($json) {
                return $json;
            }
        }

        return null;
    }

    private function htmlEncodedData(array $data)
    {
        if (null !== $data) {
            $html = "<table border=\"1\">";
                if (count($data) > 0) {
                    foreach ($data as $key => $value) {
                        $html .= "<tr>";
                            $html .= "<td>{$key}</td>";
                            $html .= "<td>{$value}</td>";
                        $html .= "</tr>";
                    }
                }
            $html .= "</table>";

            return $html;
        }

        return null;
    }

    private function xmlEncodedData(array $data)
    {
        if (null !== $data) {
            $xml = new \SimpleXMLElement('<?xml version="1.0"?><mobile></mobile>');
            foreach ($data as $key => $value) {
                $xml->addChild($key, $value);
            }
            return $xml->asXML();
        }

        return null;
    }

}