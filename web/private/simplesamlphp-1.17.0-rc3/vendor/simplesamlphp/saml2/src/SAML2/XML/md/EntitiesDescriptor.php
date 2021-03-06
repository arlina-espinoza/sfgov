<?php

namespace SAML2\XML\md;

use SAML2\Constants;
use SAML2\DOMDocumentFactory;
use SAML2\SignedElementHelper;
use SAML2\Utils;
use SAML2\XML\Chunk;

/**
 * Class representing SAML 2 EntitiesDescriptor element.
 *
 * @package SimpleSAMLphp
 */
class EntitiesDescriptor extends SignedElementHelper
{
    /**
     * The ID of this element.
     *
     * @var string|null
     */
    public $ID;

    /**
     * How long this element is valid, as a unix timestamp.
     *
     * @var int|null
     */
    public $validUntil;

    /**
     * The length of time this element can be cached, as string.
     *
     * @var string|null
     */
    public $cacheDuration;

    /**
     * The name of this entity collection.
     *
     * @var string|null
     */
    public $Name;

    /**
     * Extensions on this element.
     *
     * Array of extension elements.
     *
     * @var array
     */
    public $Extensions = [];

    /**
     * Child EntityDescriptor and EntitiesDescriptor elements.
     *
     * @var (\SAML2\XML\md\EntityDescriptor|\SAML2\XML\md\EntitiesDescriptor)[]
     */
    public $children = [];

    /**
     * Initialize an EntitiesDescriptor.
     *
     * @param \DOMElement|null $xml The XML element we should load.
     */
    public function __construct(\DOMElement $xml = null)
    {
        parent::__construct($xml);

        if ($xml === null) {
            return;
        }

        if ($xml->hasAttribute('ID')) {
            $this->setID($xml->getAttribute('ID'));
        }
        if ($xml->hasAttribute('validUntil')) {
            $this->setValidUntil(Utils::xsDateTimeToTimestamp($xml->getAttribute('validUntil')));
        }
        if ($xml->hasAttribute('cacheDuration')) {
            $this->setCacheDuration($xml->getAttribute('cacheDuration'));
        }
        if ($xml->hasAttribute('Name')) {
            $this->setName($xml->getAttribute('Name'));
        }

        $this->setExtensions(Extensions::getList($xml));

        foreach (Utils::xpQuery($xml, './saml_metadata:EntityDescriptor|./saml_metadata:EntitiesDescriptor') as $node) {
            if ($node->localName === 'EntityDescriptor') {
                $this->children[] = new EntityDescriptor($node);
            } else {
                $this->children[] = new EntitiesDescriptor($node);
            }
        }
    }

    /**
     * Collect the value of the Name-property
     * @return string|null
     */
    public function getName()
    {
        return $this->Name;
    }

    /**
     * Set the value of the Name-property
     * @param string|null $name
     */
    public function setName($name = null)
    {
        assert(is_string($name) || is_null($name));
        $this->Name = $name;
    }

    /**
     * Collect the value of the ID-property
     * @return string|null
     */
    public function getID()
    {
        return $this->ID;
    }

    /**
     * Set the value of the ID-property
     * @param string|null $Id
     */
    public function setID($Id = null)
    {
        assert(is_string($Id) || is_null($Id));
        $this->ID = $Id;
    }

    /**
     * Collect the value of the validUntil-property
     * @return int|null
     */
    public function getValidUntil()
    {
        return $this->validUntil;
    }

    /**
     * Set the value of the validUntil-property
     * @param int|null $validUntil
     */
    public function setValidUntil($validUntil = null)
    {
        assert(is_int($validUntil) || is_null($validUntil));
        $this->validUntil = $validUntil;
    }

    /**
     * Collect the value of the cacheDuration-property
     * @return string|null
     */
    public function getCacheDuration()
    {
        return $this->cacheDuration;
    }

    /**
     * Set the value of the cacheDuration-property
     * @param string|null $cacheDuration
     */
    public function setCacheDuration($cacheDuration = null)
    {
        assert(is_string($cacheDuration) || is_null($cacheDuration));
        $this->cacheDuration = $cacheDuration;
    }

    /**
     * Collect the value of the Extensions-property
     * @return \SAML2\XML\Chunk[]
     */
    public function getExtensions()
    {
        return $this->Extensions;
    }

    /**
     * Set the value of the Extensions-property
     * @param array $extensions
     */
    public function setExtensions(array $extensions)
    {
        $this->Extensions = $extensions;
    }

    /**
     * Add an Extension.
     *
     * @param \SAML2\XML\Chunk $extensions The Extensions
     */
    public function addExtension(Extensions $extension)
    {
        $this->Extensions[] = $extension;
    }

    /**
     * Collect the value of the children-property
     * @return (\SAML2\XML\md\EntityDescriptor|\SAML2\XML\md\EntitiesDescriptor)[]
     */
    public function getChildren()
    {
        return $this->children;
    }

    /**
     * Set the value of the childen-property
     * @param array $children
     */
    public function setChildren(array $children)
    {
        $this->children = $children;
    }

    /**
     * Add the value to the children-property
     * @param \SAML2\XML\md\EntityDescriptor|\SAML2\XML\md\EntitiesDescriptor $child
     */
    public function addChildren($child)
    {
        assert($child instanceof EntityDescriptor || $child instanceof EntitiesDescriptor);
        $this->children[] = $child;
    }

    /**
     * Convert this EntitiesDescriptor to XML.
     *
     * @param \DOMElement|null $parent The EntitiesDescriptor we should append this EntitiesDescriptor to.
     * @return \DOMElement
     */
    public function toXML(\DOMElement $parent = null)
    {
        assert(is_null($this->getID()) || is_string($this->getID()));
        assert(is_null($this->getValidUntil()) || is_int($this->getValidUntil()));
        assert(is_null($this->getCacheDuration()) || is_string($this->getCacheDuration()));
        assert(is_null($this->getName()) || is_string($this->getName()));
        assert(is_array($this->getExtensions()));
        assert(is_array($this->getChildren()));

        if ($parent === null) {
            $doc = DOMDocumentFactory::create();
            $e = $doc->createElementNS(Constants::NS_MD, 'md:EntitiesDescriptor');
            $doc->appendChild($e);
        } else {
            $e = $parent->ownerDocument->createElementNS(Constants::NS_MD, 'md:EntitiesDescriptor');
            $parent->appendChild($e);
        }

        if ($this->getID() !== null) {
            $e->setAttribute('ID', $this->getID());
        }

        if ($this->getValidUntil() !== null) {
            $e->setAttribute('validUntil', gmdate('Y-m-d\TH:i:s\Z', $this->getValidUntil()));
        }

        if ($this->getCacheDuration() !== null) {
            $e->setAttribute('cacheDuration', $this->getCacheDuration());
        }

        if ($this->getName() !== null) {
            $e->setAttribute('Name', $this->getName());
        }

        Extensions::addList($e, $this->getExtensions());

        /** @var \SAML2\XML\md\EntityDescriptor|\SAML2\XML\md\EntitiesDescriptor $node */
        foreach ($this->getChildren() as $node) {
            $node->toXML($e);
        }

        $this->signElement($e, $e->firstChild);

        return $e;
    }
}
