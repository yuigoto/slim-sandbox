<?php
namespace API\Entity\Users;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use YX\Entity\BaseMinEntity;

/**
 * API\Entity\Users\UserAttribute
 * ----------------------------------------------------------------------
 * Represents a single user attribute, usually grouped in collections.
 *
 * An attribute is any extra field or meta information for the user, which
 * isn't required as "basic" for the system to properly work.
 *
 * Information like:
 * - First, middle and last names;
 * - Birthdays;
 * - Telephone numbers;
 * - Secondary e-mail address;
 *
 * @package     API\Entity\Users
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 *
 * @Entity
 * @Table(name="user_attribute")
 * @HasLifecycleCallbacks
 */
class UserAttribute extends BaseMinEntity
{
    // Protected Properties
    // ------------------------------------------------------------------
    
    /**
     * @var string
     * @Column(type="string",length=128,nullable=false)
     */
    protected $name;
    
    /**
     * @var string
     * @Column(type="string",length=4096,nullable=true)
     */
    protected $value;
    
    /**
     * @var string
     * @Column(type="string",length=128,nullable=true)
     */
    protected $label = "";
    
    /**
     * @var string
     * @Column(type="string",length=128,nullable=true)
     */
    protected $type = "string";
    
    /**
     * @var string
     * @Column(type="string",length=2048,nullable=true)
     */
    protected $description = null;
    
    // Relationships
    // ------------------------------------------------------------------
    
    /**
     * @var User
     * @ManyToOne(
     *     targetEntity="API\Entity\Users\User",
     *     inversedBy="attributes"
     * )
     * @JoinColumn(name="user_id",referencedColumnName="id")
     */
    protected $user;
    
    // Getters
    // ------------------------------------------------------------------
    
    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
    
    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }
    
    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }
    
    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }
    
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }
    
    // Setters
    // ------------------------------------------------------------------
    
    /**
     * @param string $name
     * @return $this
     */
    public function setName( string $name )
    {
        $this->name = $name;
        return $this;
    }
    
    /**
     * @param string $value
     * @return $this
     */
    public function setValue( string $value )
    {
        $this->value = $value;
        return $this;
    }
    
    /**
     * @param string $label
     * @return $this
     */
    public function setLabel( string $label )
    {
        $this->label = $label;
        return $this;
    }
    
    /**
     * @param string $type
     * @return $this
     */
    public function setType( string $type )
    {
        $this->type = $type;
        return $this;
    }
    
    /**
     * @param string $description
     * @return $this
     */
    public function setDescription( string $description )
    {
        $this->description = $description;
        return $this;
    }
    
    /**
     * @param User $user
     * @return $this
     */
    public function setUser( User $user )
    {
        $this->user = $user;
        return $this;
    }
}
