<?php

namespace SAML2\XML\md;

use SAML2\Constants;
use SAML2\Utils;
use SAML2\XML\Chunk;

/**
 * Class representing SAML 2 ContactPerson.
 *
 * @package SimpleSAMLphp
 */
class ContactPerson
{
    /**
     * The contact type.
     *
     * @var string
     */
    public $contactType;

    /**
     * Extensions on this element.
     *
     * Array of extension elements.
     *
     * @var array
     */
    public $Extensions = [];

    /**
     * The Company of this contact.
     *
     * @var string
     */
    public $Company = null;

    /**
     * The GivenName of this contact.
     *
     * @var string
     */
    public $GivenName = null;

    /**
     * The SurName of this contact.
     *
     * @var string
     */
    public $SurName = null;

    /**
     * The EmailAddresses of this contact.
     *
     * @var array
     */
    public $EmailAddress = [];

    /**
     * The TelephoneNumbers of this contact.
     *
     * @var array
     */
    public $TelephoneNumber = [];

    /**
     * Extra attributes on the contact element.
     *
     * @var array
     */
    public $ContactPersonAttributes = [];

    /**
     * Initialize a ContactPerson element.
     *
     * @param \DOMElement|null $xml The XML element we should load.
     * @throws \Exception
     */
    public function __construct(\DOMElement $xml = null)
    {
        if ($xml === null) {
            return;
        }

        if (!$xml->hasAttribute('contactType')) {
            throw new \Exception('Missing contactType on ContactPerson.');
        }
        $this->setContactType($xml->getAttribute('contactType'));

        $this->setExtensions(Extensions::getList($xml));

        $this->setCompany(self::getStringElement($xml, 'Company'));
        $this->setGivenName(self::getStringElement($xml, 'GivenName'));
        $this->setSurName(self::getStringElement($xml, 'SurName'));
        $this->setEmailAddress(self::getStringElements($xml, 'EmailAddress'));
        $this->setTelephoneNumber(self::getStringElements($xml, 'TelephoneNumber'));

        foreach ($xml->attributes as $attr) {
            if ($attr->nodeName == "contactType") {
                continue;
            }

            $this->addContactPersonAttributes($attr->nodeName, $attr->nodeValue);
        }
    }

    /**
     * Retrieve the value of a child \DOMElements as an array of strings.
     *
     * @param  \DOMElement $parent The parent element.
     * @param  string     $name   The name of the child elements.
     * @return array      The value of the child elements.
     */
    private static function getStringElements(\DOMElement $parent, $name)
    {
        assert(is_string($name));

        $e = Utils::xpQuery($parent, './saml_metadata:'.$name);

        $ret = [];
        foreach ($e as $i) {
            $ret[] = $i->textContent;
        }

        return $ret;
    }

    /**
     * Retrieve the value of a child \DOMElement as a string.
     *
     * @param  \DOMElement  $parent The parent element.
     * @param  string      $name   The name of the child element.
     * @return string|null The value of the child element.
     * @throws \Exception
     */
    private static function getStringElement(\DOMElement $parent, $name)
    {
        assert(is_string($name));

        $e = self::getStringElements($parent, $name);
        if (empty($e)) {
            return null;
        }
        if (count($e) > 1) {
            throw new \Exception('More than one '.$name.' in '.$parent->tagName);
        }

        return $e[0];
    }

    /**
     * Collect the value of the contactType-property
     * @return string
     */
    public function getContactType()
    {
        return $this->contactType;
    }

    /**
     * Set the value of the contactType-property
     * @param string $contactType
     */
    public function setContactType($contactType)
    {
        assert(is_string($contactType));
        $this->contactType = $contactType;
    }

    /**
     * Collect the value of the Company-property
     * @return string|null
     */
    public function getCompany()
    {
        return $this->Company;
    }

    /**
     * Set the value of the Company-property
     * @param string|null $company
     */
    public function setCompany($company)
    {
        assert(is_string($company) || is_null($company));
        $this->Company = $company;
    }

    /**
     * Collect the value of the GivenName-property
     * @return string|null
     */
    public function getGivenName()
    {
        return $this->GivenName;
    }

    /**
     * Set the value of the GivenName-property
     * @param string|null $givenName
     */
    public function setGivenName($givenName)
    {
        assert(is_string($givenName) || is_null($givenName));
        $this->GivenName = $givenName;
    }

    /**
     * Collect the value of the SurName-property
     * @return string|null
     */
    public function getSurName()
    {
        return $this->SurName;
    }

    /**
     * Set the value of the SurName-property
     * @param string|null $surName
     */
    public function setSurName($surName)
    {
        assert(is_string($surName) || is_null($surName));
        $this->SurName = $surName;
    }

    /**
     * Collect the value of the EmailAddress-property
     * @return string[]
     */
    public function getEmailAddress()
    {
        return $this->EmailAddress;
    }

    /**
     * Set the value of the EmailAddress-property
     * @param string[] $emailAddress
     */
    public function setEmailAddress(array $emailAddress)
    {
        $this->EmailAddress = $emailAddress;
    }

    /**
     * Add the value to the EmailAddress-property
     * @param string $emailAddress
     */
    public function addEmailAddress($emailAddress)
    {
        $this->EmailAddress[] = $emailAddress;
    }

    /**
     * Collect the value of the TelephoneNumber-property
     * @return string[]
     */
    public function getTelephoneNumber()
    {
        return $this->TelephoneNumber;
    }

    /**
     * Set the value of the TelephoneNumber-property
     * @param string[] $telephoneNumber
     */
    public function setTelephoneNumber(array $telephoneNumber)
    {
        $this->TelephoneNumber = $telephoneNumber;
    }

    /**
     * Add the value to the TelephoneNumber-property
     * @param string $telephoneNumber
     */
    public function addTelephoneNumber($telephoneNumber)
    {
        $this->TelephoneNumber[] = $telephoneNumber;
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
    public function addExtension(Chunk $extension)
    {
        $this->Extensions[] = $extension;
    }

    /**
     * Collect the value of the ContactPersonAttributes-property
     * @return string[]
     */
    public function getContactPersonAttributes()
    {
        return $this->ContactPersonAttributes;
    }

    /**
     * Set the value of the ContactPersonAttributes-property
     * @param string[] $contactPersonAttributes
     */
    public function setContactPersonAttributes(array $contactPersonAttributes)
    {
        $this->ContactPersonAttributes = $contactPersonAttributes;
    }

    /**
     * Add the key/value of the ContactPersonAttributes-property
     * @param string $attr
     * @param string $value
     */
    public function addContactPersonAttributes($attr, $value)
    {
        assert(is_string($attr));
        assert(is_string($value));
        $this->ContactPersonAttributes[$attr] = $value;
    }

    /**
     * Convert this ContactPerson to XML.
     *
     * @param  \DOMElement $parent The element we should add this contact to.
     * @return \DOMElement The new ContactPerson-element.
     */
    public function toXML(\DOMElement $parent)
    {
        assert(is_string($this->getContactType()));
        assert(is_array($this->getExtensions()));
        assert(is_null($this->getCompany()) || is_string($this->getCompany()));
        assert(is_null($this->getGivenName()) || is_string($this->getGivenName()));
        assert(is_null($this->getSurName()) || is_string($this->getSurName()));
        assert(is_array($this->getEmailAddress()));
        assert(is_array($this->getTelephoneNumber()));
        assert(is_array($this->getContactPersonAttributes()));

        $doc = $parent->ownerDocument;

        $e = $doc->createElementNS(Constants::NS_MD, 'md:ContactPerson');
        $parent->appendChild($e);

        $e->setAttribute('contactType', $this->getContactType());

        foreach ($this->getContactPersonAttributes() as $attr => $val) {
            $e->setAttribute($attr, $val);
        }

        Extensions::addList($e, $this->getExtensions());

        if ($this->getCompany() !== null) {
            Utils::addString($e, Constants::NS_MD, 'md:Company', $this->getCompany());
        }
        if ($this->getGivenName() !== null) {
            Utils::addString($e, Constants::NS_MD, 'md:GivenName', $this->getGivenName());
        }
        if ($this->getSurName() !== null) {
            Utils::addString($e, Constants::NS_MD, 'md:SurName', $this->getSurName());
        }
        if ($this->getEmailAddress() !== null) {
            Utils::addStrings($e, Constants::NS_MD, 'md:EmailAddress', false, $this->getEmailAddress());
        }
        if ($this->getTelephoneNumber() !== null) {
            Utils::addStrings($e, Constants::NS_MD, 'md:TelephoneNumber', false, $this->getTelephoneNumber());
        }

        return $e;
    }
}
