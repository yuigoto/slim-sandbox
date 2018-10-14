<?php
namespace API\Entity\Users;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\JoinTable;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use YX\Entity\BaseEntity;

/**
 * API\Entity\Users\UserRole
 * ----------------------------------------------------------------------
 * Represents a single user role.
 *
 * User roles have capabilities, which tell what the user can do on the
 * system.
 *
 * Users have a single role assigned to them, which gives them a collection
 * of capabilities.
 *
 * @package     API\Entity\Users
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 *
 * @Entity
 * @Table(name="user_role")
 * @HasLifecycleCallbacks
 */
class UserRole extends BaseEntity
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
     *     targetEntity="API\Entity\Users\UserCapability",
     *     inversedBy="roles"
     * )
     * @JoinTable(
     *     name="user_role_capability",
     *     joinColumns={
     *          @JoinColumn(name="role_id",referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @JoinColumn(name="capability_id",referencedColumnName="id")
     *     }
     * )
     */
    protected $capabilities;
    
    /**
     * @var Collection
     * @OneToMany(
     *     targetEntity="API\Entity\Users\User",
     *     mappedBy="role"
     * )
     */
    protected $users;
    
    // Constructor/Destructor
    // ------------------------------------------------------------------
    
    /**
     * UserRole constructor.
     */
    public function __construct()
    {
        $this->capabilities = new ArrayCollection();
        $this->users = new ArrayCollection();
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
    public function getCapabilities(): Collection
    {
        return $this->capabilities;
    }
    
    /**
     * @return Collection
     */
    public function getUsers(): Collection
    {
        return $this->users;
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
     * @param UserCapability $capability
     * @return $this
     */
    public function addCapability( UserCapability $capability )
    {
        $this->capabilities[] = $capability;
        return $this;
    }
    
    /**
     * @param int $capability_id
     * @return $this
     */
    public function removeCapability( int $capability_id )
    {
        /** @var UserCapability $capability */
        foreach ( $this->capabilities as $capability ) {
            if ( $capability->getId() === $capability_id ) {
                $this->capabilities->removeElement( $capability );
            }
        }
        return $this;
    }
    
    /**
     * @param User $user
     * @return $this
     */
    public function addUser( User $user )
    {
        $this->users[] = $user;
        return $this;
    }
    
    /**
     * @param int $user_id
     * @return $this
     */
    public function removeUser( int $user_id )
    {
        /** @var User $user */
        foreach ( $this->users as $user ) {
            if ( $user->getId() === $user_id ) {
                $this->users->removeElement( $user );
            }
        }
        return $this;
    }
}
