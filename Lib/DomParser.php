<?php

namespace Lib;

use \Lib\String;

/**
 * Utility to parse the content of an Xml file
 * @depends \Lib\String
 */
class DomParser
{
    /**
     * @var \DOMDocument
     */
    protected $domBody;
    /**
     * @var \DOMXPath
     */
    protected $domXPath;

    public function __construct($rawBody)
    {
        $this->domBody = new \DomDocument();
        @$this->domBody->loadHTML('<?xml encoding="UTF-8">'.$rawBody);
        $this->domXPath = new \DomXPath($this->domBody);
    }

    /**
     * Get a node value by tag name
     * @param string $tagName Node
     * @param string $regExtract Regular expression to extract what is needed (use a capturing brackets)
     * @return string Node value
     */
    public function getFirstValueByTagName($tagName, $regExtract = null)
    {
        if (!is_string($tagName)) {
            return false;
        }

        foreach ($this->domBody->getElementsByTagName($tagName) as $node) {
            if (empty($regExtract)) {
                return String::normalize($node->nodeValue);
            } else {
                return $this->regExtract($regExtract, String::normalize($node->nodeValue));
            }
        }

        return null;
    }

    /**
     * Get a node value by id
     * @param string $id DOM Id
     * @param string $regExtract Regular expression to extract what is needed (use a capturing brackets)
     * @return string Node value or null if nothing is found or false if an error occured
     */
    public function getFirstValueById($id, $regExtract = null)
    {
        if (!is_string($id)) {
            return false;
        }

        $extract = $this->domBody->getElementById($id);
        if (!is_null($extract)) {
            if (empty($regExtract)) {
                return String::normalize($extract->nodeValue);
            } else {
                return $this->regExtract($regExtract, String::normalize($extract->nodeValue));
            }
        } else {
            return null;
        }
    }

    /**
     * Get a node by class
     * @param string $class DOM class
     * @param string $regExtract Regular expression to extract what is needed (use a capturing brackets)
     * @return string Node value or null if nothing is found or false if an error occured
     */
    public function getFirstValueByClass($class, $regExtract = null)
    {
        if (!is_string($class)) {
            return false;
        }

        foreach ($this->domXPath->query("//*[@class='$class' or contains(@class, ' $class') or contains(@class, '$class ')]") as $node) {
            if (empty($regExtract)) {
                return String::normalize($node->nodeValue);
            } else {
                return $this->regExtract($regExtract, String::normalize($node->nodeValue));
            }
        }

        return null;
    }

    /**
     * Get all the image sources inside a block defined by an id
     * @param string $id DOM id
     * @return string[] Images urls
     */
    public function getAllImageUrlInsideId($id)
    {
        if (!is_string($id)) {
            return false;
        }

        $urls = array();
        $extract = $this->domBody->getElementById($id);

        if ($extract->tagName == 'img' && !empty($extract->getAttribute('src'))) {
            $urls[] = $extract->getAttribute('src');
        }

        foreach ($this->domBody->getElementById($id)->getElementsByTagName('img') as $image) {
            $urls[] = $image->getAttribute('src');
        }

        return $urls;
    }

    /**
     * Get all the image sources inside a block defined by a class
     * @param string $class DOM class
     * @return string Images urls
     */
    public function getAllImageUrlInsideClass($class)
    {
        if (!is_string($class)) {
            return false;
        }

        $urls = array();

        foreach ($this->domXPath->query("//*[@class='$class' or contains(@class, ' $class') or contains(@class, '$class ')]") as $node) {
            foreach ($node->getElementsByTagName('img') as $image) {
                return $image->getAttribute('src');
            }
        }

        return $urls;
    }

    /**
     * Slim the parent DomParser into another sharpened DomParser with the root node corresponding to the id
     * @param string $id Dom id
     * @return \self or null if nothing is captured by the id or false if an error occured
     */
    public function slimDomParserById($id)
    {
        if (!is_string($id)) {
            return false;
        }

        $extract = $this->domBody->getElementById($id);
        if (is_null($extract)) {
            return null;
        }

        return new self($this->domBody->saveXML($extract));
    }

    /**
     * Slim the parent DomParser into another sharpened DomParser with the root node corresponding to the class
     * @param string $class Dom id
     * @return \self or null if nothing is captured by the id or false if an error occured
     */
    public function slimDomParserByClass($class)
    {
        if (!is_string($class)) {
            return false;
        }

        foreach ($this->domXPath->query("//*[@class='$class' or contains(@class, ' $class') or contains(@class, '$class ')]") as $node) {
            return new self($this->domBody->saveXML($node));
        }

        return null;
    }

    /**
     * @return \DomDocument
     */
    public function getDomBody()
    {
        return $this->domBody;
    }

    /**
     * @return \DOMXPath
     */
    public function getDomXPath()
    {
        return $this->domXPath;
    }

    /**
     * Extract a value from string with a regular expression
     * @param string $regexp Regular expression
     * @param string $value The main string to apply the regular expression
     * @return string The found string or false if regexp is invalid or the $value string if nothing is found
     */
    private function regExtract($regexp, $value)
    {
        preg_match($regexp, $value, $matches);
        if (!empty($matches[1])) {
            return $matches[1];
        } else {
            return $value;
        }
    }
}
