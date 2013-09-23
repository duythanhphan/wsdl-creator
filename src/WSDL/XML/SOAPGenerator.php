<?php
/**
 * SOAPGenerator
 *
 * @author Piotr Olaszewski <piotroo89 [%] gmail dot com>
 */
namespace WSDL\XML;

use DOMDocument;
use WSDL\Types\Simple;

class SOAPGenerator
{
    private $_types;
    private $_methodName;
    private $_serviceUrl;

    function __construct($types, $methodName, $serviceUrl)
    {
        $this->_types = $types;
        $this->_methodName = $methodName;
        $this->_serviceUrl = $serviceUrl;
    }

    public function getXML()
    {
        $DOMDocument = new DOMDocument();
        $DOMDocument->formatOutput = true;
        $DOMDocument->preserveWhiteSpace = false;

        $envelope = $DOMDocument->createElementNS('http://schemas.xmlsoap.org/soap/envelope/', 'Envelope');
        $envelope->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:elem', $this->_serviceUrl);

        $body = $DOMDocument->createElement('Body');
        $method = $DOMDocument->createElement('elem:' . $this->_methodName);
        foreach ($this->_types as $type) {
            if ($type instanceof Simple) {
                $element = $DOMDocument->createElement($type->getName(), '?');
                $method->appendChild($element);
            }
        }
        $body->appendChild($method);
        $envelope->appendChild($body);

        $DOMDocument->appendChild($envelope);

        return $DOMDocument->saveXML();
    }
}