<?php
/**
 * DocumentSearch
 *
 * PHP version 5
 *
 * @category Class
 * @package  SignRequest
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */

/**
 * SignRequest API
 *
 * API for SignRequest.com
 * OpenAPI spec version: v1
 * Contact: tech-support@signrequest.com
 * Generated by: https://github.com/swagger-api/swagger-codegen.git
 * Swagger Codegen version: 2.4.11
 */
/**
 * NOTE: This class is auto generated by the swagger code generator program.
 * https://github.com/swagger-api/swagger-codegen
 * Do not edit the class manually.
 */

namespace SignRequest\Model;

use \ArrayAccess;
use \SignRequest\ObjectSerializer;

/**
 * DocumentSearch Class Doc Comment
 *
 * @category Class
 * @package  SignRequest
 * @author   Swagger Codegen team
 * @link     https://github.com/swagger-api/swagger-codegen
 */
class DocumentSearch implements ModelInterface, ArrayAccess
{
    const DISCRIMINATOR = null;

    /**
      * The original name of the model.
      *
      * @var string
      */
    protected static $swaggerModelName = 'DocumentSearch';

    /**
      * Array of property to type mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerTypes = [
        'uuid' => 'string',
        'created' => '\DateTime',
        'status' => 'string',
        'who' => 'string',
        'name' => 'string',
        'autocomplete' => 'string',
        'from_email' => 'string',
        'nr_extra_docs' => 'int',
        'signer_emails' => 'string[]',
        'status_display' => 'string',
        'created_timestamp' => 'int',
        'finished_on_timestamp' => 'int',
        'parent_doc' => 'string',
        'finished_on' => '\DateTime',
        'subdomain' => 'string'
    ];

    /**
      * Array of property to format mappings. Used for (de)serialization
      *
      * @var string[]
      */
    protected static $swaggerFormats = [
        'uuid' => null,
        'created' => 'date-time',
        'status' => null,
        'who' => null,
        'name' => null,
        'autocomplete' => null,
        'from_email' => null,
        'nr_extra_docs' => null,
        'signer_emails' => null,
        'status_display' => null,
        'created_timestamp' => null,
        'finished_on_timestamp' => null,
        'parent_doc' => null,
        'finished_on' => 'date-time',
        'subdomain' => null
    ];

    /**
     * Array of property to type mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerTypes()
    {
        return self::$swaggerTypes;
    }

    /**
     * Array of property to format mappings. Used for (de)serialization
     *
     * @return array
     */
    public static function swaggerFormats()
    {
        return self::$swaggerFormats;
    }

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @var string[]
     */
    protected static $attributeMap = [
        'uuid' => 'uuid',
        'created' => 'created',
        'status' => 'status',
        'who' => 'who',
        'name' => 'name',
        'autocomplete' => 'autocomplete',
        'from_email' => 'from_email',
        'nr_extra_docs' => 'nr_extra_docs',
        'signer_emails' => 'signer_emails',
        'status_display' => 'status_display',
        'created_timestamp' => 'created_timestamp',
        'finished_on_timestamp' => 'finished_on_timestamp',
        'parent_doc' => 'parent_doc',
        'finished_on' => 'finished_on',
        'subdomain' => 'subdomain'
    ];

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @var string[]
     */
    protected static $setters = [
        'uuid' => 'setUuid',
        'created' => 'setCreated',
        'status' => 'setStatus',
        'who' => 'setWho',
        'name' => 'setName',
        'autocomplete' => 'setAutocomplete',
        'from_email' => 'setFromEmail',
        'nr_extra_docs' => 'setNrExtraDocs',
        'signer_emails' => 'setSignerEmails',
        'status_display' => 'setStatusDisplay',
        'created_timestamp' => 'setCreatedTimestamp',
        'finished_on_timestamp' => 'setFinishedOnTimestamp',
        'parent_doc' => 'setParentDoc',
        'finished_on' => 'setFinishedOn',
        'subdomain' => 'setSubdomain'
    ];

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @var string[]
     */
    protected static $getters = [
        'uuid' => 'getUuid',
        'created' => 'getCreated',
        'status' => 'getStatus',
        'who' => 'getWho',
        'name' => 'getName',
        'autocomplete' => 'getAutocomplete',
        'from_email' => 'getFromEmail',
        'nr_extra_docs' => 'getNrExtraDocs',
        'signer_emails' => 'getSignerEmails',
        'status_display' => 'getStatusDisplay',
        'created_timestamp' => 'getCreatedTimestamp',
        'finished_on_timestamp' => 'getFinishedOnTimestamp',
        'parent_doc' => 'getParentDoc',
        'finished_on' => 'getFinishedOn',
        'subdomain' => 'getSubdomain'
    ];

    /**
     * Array of attributes where the key is the local name,
     * and the value is the original name
     *
     * @return array
     */
    public static function attributeMap()
    {
        return self::$attributeMap;
    }

    /**
     * Array of attributes to setter functions (for deserialization of responses)
     *
     * @return array
     */
    public static function setters()
    {
        return self::$setters;
    }

    /**
     * Array of attributes to getter functions (for serialization of requests)
     *
     * @return array
     */
    public static function getters()
    {
        return self::$getters;
    }

    /**
     * The original name of the model.
     *
     * @return string
     */
    public function getModelName()
    {
        return self::$swaggerModelName;
    }

    

    

    /**
     * Associative array for storing property values
     *
     * @var mixed[]
     */
    protected $container = [];

    /**
     * Constructor
     *
     * @param mixed[] $data Associated array of property values
     *                      initializing the model
     */
    public function __construct(array $data = null)
    {
        $this->container['uuid'] = isset($data['uuid']) ? $data['uuid'] : null;
        $this->container['created'] = isset($data['created']) ? $data['created'] : null;
        $this->container['status'] = isset($data['status']) ? $data['status'] : null;
        $this->container['who'] = isset($data['who']) ? $data['who'] : null;
        $this->container['name'] = isset($data['name']) ? $data['name'] : null;
        $this->container['autocomplete'] = isset($data['autocomplete']) ? $data['autocomplete'] : null;
        $this->container['from_email'] = isset($data['from_email']) ? $data['from_email'] : null;
        $this->container['nr_extra_docs'] = isset($data['nr_extra_docs']) ? $data['nr_extra_docs'] : null;
        $this->container['signer_emails'] = isset($data['signer_emails']) ? $data['signer_emails'] : null;
        $this->container['status_display'] = isset($data['status_display']) ? $data['status_display'] : null;
        $this->container['created_timestamp'] = isset($data['created_timestamp']) ? $data['created_timestamp'] : null;
        $this->container['finished_on_timestamp'] = isset($data['finished_on_timestamp']) ? $data['finished_on_timestamp'] : null;
        $this->container['parent_doc'] = isset($data['parent_doc']) ? $data['parent_doc'] : null;
        $this->container['finished_on'] = isset($data['finished_on']) ? $data['finished_on'] : null;
        $this->container['subdomain'] = isset($data['subdomain']) ? $data['subdomain'] : null;
    }

    /**
     * Show all the invalid properties with reasons.
     *
     * @return array invalid properties with reasons
     */
    public function listInvalidProperties()
    {
        $invalidProperties = [];

        if (!is_null($this->container['uuid']) && (mb_strlen($this->container['uuid']) < 1)) {
            $invalidProperties[] = "invalid value for 'uuid', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['status']) && (mb_strlen($this->container['status']) > 2)) {
            $invalidProperties[] = "invalid value for 'status', the character length must be smaller than or equal to 2.";
        }

        if (!is_null($this->container['status']) && (mb_strlen($this->container['status']) < 1)) {
            $invalidProperties[] = "invalid value for 'status', the character length must be bigger than or equal to 1.";
        }

        if ($this->container['who'] === null) {
            $invalidProperties[] = "'who' can't be null";
        }
        if ((mb_strlen($this->container['who']) < 1)) {
            $invalidProperties[] = "invalid value for 'who', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['name']) && (mb_strlen($this->container['name']) < 1)) {
            $invalidProperties[] = "invalid value for 'name', the character length must be bigger than or equal to 1.";
        }

        if ($this->container['autocomplete'] === null) {
            $invalidProperties[] = "'autocomplete' can't be null";
        }
        if ((mb_strlen($this->container['autocomplete']) < 1)) {
            $invalidProperties[] = "invalid value for 'autocomplete', the character length must be bigger than or equal to 1.";
        }

        if ($this->container['from_email'] === null) {
            $invalidProperties[] = "'from_email' can't be null";
        }
        if ((mb_strlen($this->container['from_email']) < 1)) {
            $invalidProperties[] = "invalid value for 'from_email', the character length must be bigger than or equal to 1.";
        }

        if ($this->container['nr_extra_docs'] === null) {
            $invalidProperties[] = "'nr_extra_docs' can't be null";
        }
        if (!is_null($this->container['status_display']) && (mb_strlen($this->container['status_display']) < 1)) {
            $invalidProperties[] = "invalid value for 'status_display', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['parent_doc']) && (mb_strlen($this->container['parent_doc']) < 1)) {
            $invalidProperties[] = "invalid value for 'parent_doc', the character length must be bigger than or equal to 1.";
        }

        if (!is_null($this->container['subdomain']) && (mb_strlen($this->container['subdomain']) < 1)) {
            $invalidProperties[] = "invalid value for 'subdomain', the character length must be bigger than or equal to 1.";
        }

        return $invalidProperties;
    }

    /**
     * Validate all the properties in the model
     * return true if all passed
     *
     * @return bool True if all properties are valid
     */
    public function valid()
    {
        return count($this->listInvalidProperties()) === 0;
    }


    /**
     * Gets uuid
     *
     * @return string
     */
    public function getUuid()
    {
        return $this->container['uuid'];
    }

    /**
     * Sets uuid
     *
     * @param string $uuid uuid
     *
     * @return $this
     */
    public function setUuid($uuid)
    {

        if (!is_null($uuid) && (mb_strlen($uuid) < 1)) {
            throw new \InvalidArgumentException('invalid length for $uuid when calling DocumentSearch., must be bigger than or equal to 1.');
        }

        $this->container['uuid'] = $uuid;

        return $this;
    }

    /**
     * Gets created
     *
     * @return \DateTime
     */
    public function getCreated()
    {
        return $this->container['created'];
    }

    /**
     * Sets created
     *
     * @param \DateTime $created created
     *
     * @return $this
     */
    public function setCreated($created)
    {
        $this->container['created'] = $created;

        return $this;
    }

    /**
     * Gets status
     *
     * @return string
     */
    public function getStatus()
    {
        return $this->container['status'];
    }

    /**
     * Sets status
     *
     * @param string $status `co`: converting, `ne`: new, `se`: sent, `vi`: viewed, `si`: signed, `do`: downloaded, `sd`: signed and downloaded, `ca`: cancelled, `de`: declined, `ec`: error converting, `es`: error sending, `xp`: expired
     *
     * @return $this
     */
    public function setStatus($status)
    {
        if (!is_null($status) && (mb_strlen($status) > 2)) {
            throw new \InvalidArgumentException('invalid length for $status when calling DocumentSearch., must be smaller than or equal to 2.');
        }
        if (!is_null($status) && (mb_strlen($status) < 1)) {
            throw new \InvalidArgumentException('invalid length for $status when calling DocumentSearch., must be bigger than or equal to 1.');
        }

        $this->container['status'] = $status;

        return $this;
    }

    /**
     * Gets who
     *
     * @return string
     */
    public function getWho()
    {
        return $this->container['who'];
    }

    /**
     * Sets who
     *
     * @param string $who who
     *
     * @return $this
     */
    public function setWho($who)
    {

        if ((mb_strlen($who) < 1)) {
            throw new \InvalidArgumentException('invalid length for $who when calling DocumentSearch., must be bigger than or equal to 1.');
        }

        $this->container['who'] = $who;

        return $this;
    }

    /**
     * Gets name
     *
     * @return string
     */
    public function getName()
    {
        return $this->container['name'];
    }

    /**
     * Sets name
     *
     * @param string $name Defaults to filename
     *
     * @return $this
     */
    public function setName($name)
    {

        if (!is_null($name) && (mb_strlen($name) < 1)) {
            throw new \InvalidArgumentException('invalid length for $name when calling DocumentSearch., must be bigger than or equal to 1.');
        }

        $this->container['name'] = $name;

        return $this;
    }

    /**
     * Gets autocomplete
     *
     * @return string
     */
    public function getAutocomplete()
    {
        return $this->container['autocomplete'];
    }

    /**
     * Sets autocomplete
     *
     * @param string $autocomplete autocomplete
     *
     * @return $this
     */
    public function setAutocomplete($autocomplete)
    {

        if ((mb_strlen($autocomplete) < 1)) {
            throw new \InvalidArgumentException('invalid length for $autocomplete when calling DocumentSearch., must be bigger than or equal to 1.');
        }

        $this->container['autocomplete'] = $autocomplete;

        return $this;
    }

    /**
     * Gets from_email
     *
     * @return string
     */
    public function getFromEmail()
    {
        return $this->container['from_email'];
    }

    /**
     * Sets from_email
     *
     * @param string $from_email from_email
     *
     * @return $this
     */
    public function setFromEmail($from_email)
    {

        if ((mb_strlen($from_email) < 1)) {
            throw new \InvalidArgumentException('invalid length for $from_email when calling DocumentSearch., must be bigger than or equal to 1.');
        }

        $this->container['from_email'] = $from_email;

        return $this;
    }

    /**
     * Gets nr_extra_docs
     *
     * @return int
     */
    public function getNrExtraDocs()
    {
        return $this->container['nr_extra_docs'];
    }

    /**
     * Sets nr_extra_docs
     *
     * @param int $nr_extra_docs nr_extra_docs
     *
     * @return $this
     */
    public function setNrExtraDocs($nr_extra_docs)
    {
        $this->container['nr_extra_docs'] = $nr_extra_docs;

        return $this;
    }

    /**
     * Gets signer_emails
     *
     * @return string[]
     */
    public function getSignerEmails()
    {
        return $this->container['signer_emails'];
    }

    /**
     * Sets signer_emails
     *
     * @param string[] $signer_emails signer_emails
     *
     * @return $this
     */
    public function setSignerEmails($signer_emails)
    {
        $this->container['signer_emails'] = $signer_emails;

        return $this;
    }

    /**
     * Gets status_display
     *
     * @return string
     */
    public function getStatusDisplay()
    {
        return $this->container['status_display'];
    }

    /**
     * Sets status_display
     *
     * @param string $status_display status_display
     *
     * @return $this
     */
    public function setStatusDisplay($status_display)
    {

        if (!is_null($status_display) && (mb_strlen($status_display) < 1)) {
            throw new \InvalidArgumentException('invalid length for $status_display when calling DocumentSearch., must be bigger than or equal to 1.');
        }

        $this->container['status_display'] = $status_display;

        return $this;
    }

    /**
     * Gets created_timestamp
     *
     * @return int
     */
    public function getCreatedTimestamp()
    {
        return $this->container['created_timestamp'];
    }

    /**
     * Sets created_timestamp
     *
     * @param int $created_timestamp created_timestamp
     *
     * @return $this
     */
    public function setCreatedTimestamp($created_timestamp)
    {
        $this->container['created_timestamp'] = $created_timestamp;

        return $this;
    }

    /**
     * Gets finished_on_timestamp
     *
     * @return int
     */
    public function getFinishedOnTimestamp()
    {
        return $this->container['finished_on_timestamp'];
    }

    /**
     * Sets finished_on_timestamp
     *
     * @param int $finished_on_timestamp finished_on_timestamp
     *
     * @return $this
     */
    public function setFinishedOnTimestamp($finished_on_timestamp)
    {
        $this->container['finished_on_timestamp'] = $finished_on_timestamp;

        return $this;
    }

    /**
     * Gets parent_doc
     *
     * @return string
     */
    public function getParentDoc()
    {
        return $this->container['parent_doc'];
    }

    /**
     * Sets parent_doc
     *
     * @param string $parent_doc parent_doc
     *
     * @return $this
     */
    public function setParentDoc($parent_doc)
    {

        if (!is_null($parent_doc) && (mb_strlen($parent_doc) < 1)) {
            throw new \InvalidArgumentException('invalid length for $parent_doc when calling DocumentSearch., must be bigger than or equal to 1.');
        }

        $this->container['parent_doc'] = $parent_doc;

        return $this;
    }

    /**
     * Gets finished_on
     *
     * @return \DateTime
     */
    public function getFinishedOn()
    {
        return $this->container['finished_on'];
    }

    /**
     * Sets finished_on
     *
     * @param \DateTime $finished_on finished_on
     *
     * @return $this
     */
    public function setFinishedOn($finished_on)
    {
        $this->container['finished_on'] = $finished_on;

        return $this;
    }

    /**
     * Gets subdomain
     *
     * @return string
     */
    public function getSubdomain()
    {
        return $this->container['subdomain'];
    }

    /**
     * Sets subdomain
     *
     * @param string $subdomain subdomain
     *
     * @return $this
     */
    public function setSubdomain($subdomain)
    {

        if (!is_null($subdomain) && (mb_strlen($subdomain) < 1)) {
            throw new \InvalidArgumentException('invalid length for $subdomain when calling DocumentSearch., must be bigger than or equal to 1.');
        }

        $this->container['subdomain'] = $subdomain;

        return $this;
    }
    /**
     * Returns true if offset exists. False otherwise.
     *
     * @param integer $offset Offset
     *
     * @return boolean
     */
    public function offsetExists($offset)
    {
        return isset($this->container[$offset]);
    }

    /**
     * Gets offset.
     *
     * @param integer $offset Offset
     *
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return isset($this->container[$offset]) ? $this->container[$offset] : null;
    }

    /**
     * Sets value based on offset.
     *
     * @param integer $offset Offset
     * @param mixed   $value  Value to be set
     *
     * @return void
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->container[] = $value;
        } else {
            $this->container[$offset] = $value;
        }
    }

    /**
     * Unsets offset.
     *
     * @param integer $offset Offset
     *
     * @return void
     */
    public function offsetUnset($offset)
    {
        unset($this->container[$offset]);
    }

    /**
     * Gets the string presentation of the object
     *
     * @return string
     */
    public function __toString()
    {
        if (defined('JSON_PRETTY_PRINT')) { // use JSON pretty print
            return json_encode(
                ObjectSerializer::sanitizeForSerialization($this),
                JSON_PRETTY_PRINT
            );
        }

        return json_encode(ObjectSerializer::sanitizeForSerialization($this));
    }
}

