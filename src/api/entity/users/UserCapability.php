<?php
namespace API\Entity\Users;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use YX\Entity\BaseMinEntity;

/**
 * API\Entity\Users\UserCapability
 * ----------------------------------------------------------------------
 * Represents a single user capability.
 *
 * Capabilities are just action names, which tell what the user can do
 * on the system.
 *
 * Capabilities are attached to user roles, which are then attached to
 * users inside a collection.
 *
 * Users shouldn't have to deal directly with capabilities.
 *
 * @package     API\Entity\Users
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 *
 * @Entity
 * @Table(name="user_capability")
 * @HasLifecycleCallbacks
 */
class UserCapability extends BaseMinEntity
{
    // Protected Properties
    // ------------------------------------------------------------------
    
    /**
     * @var string
     * @Column(type="string",length=128,nullable=false,unique=true)
     */
    protected $name;
    
    /**
     * @var string
     * @Column(type="string",length=128,nullable=false,unique=true)
     */
    protected $slug;
    
    /**
     * @var string
     * @Column(type="string",length=255,nullable=true)
     */
    protected $description = "";
    
    // Relationships
    // ------------------------------------------------------------------
    
    /**
     * @var Collection
     * @ManyToMany(
     *     targetEntity="API\Entity\Users\UserRole",
     *     mappedBy="capabilities"
     * )
     */
    protected $roles;
    
    // Constructor/Destructor
    // ------------------------------------------------------------------
    
    /**
     * UserCapability constructor.
     */
    public function __construct()
    {
        $this->roles = new ArrayCollection();
    }
    
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
    public function getSlug(): string
    {
        return $this->slug;
    }
    
    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    /**
     * @return Collection
     */
    public function getRoles(): Collection
    {
        return $this->roles;
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
     * @param string $slug
     * @return $this
     */
    public function setSlug( string $slug )
    {
        $this->slug = $slug;
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
    
    // Collection Managers
    // ------------------------------------------------------------------
    
    /**
     * @param UserRole $role
     * @return $this
     */
    public function addRole( UserRole $role )
    {
        $this->roles[] = $role;
        return $this;
    }
    
    /**
     * @param int $role_id
     * @return $this
     */
    public function removeRole( int $role_id )
    {
        /** @var UserRole $role */
        foreach ( $this->roles as $role ) {
            if ( $role->getId() === $role_id ) {
                $this->roles->removeElement( $role );
            }
        }
        return $this;
    }
}
