<?php
namespace API\Entity\Users;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\Table;
use YX\Entity\BaseProfileEntity;

/**
 * API\Entity\Users\UserGroup
 * ----------------------------------------------------------------------
 * Represents a single group.
 *
 * Users can join/be assigned to one or more groups, which are kept in a
 * collection.
 *
 * Groups are like company groups, interest groups or anything related.
 *
 * @package     API\Entity\Users
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 *
 * @Entity
 * @Table(name="user_group")
 * @HasLifecycleCallbacks
 */
class UserGroup extends BaseProfileEntity
{
    // Protected Properties
    // ------------------------------------------------------------------
    
    /**
     * @var string
     * @Column(type="string",length=128,unique=true,nullable=false)
     */
    protected $name;
    
    /**
     * @var string
     * @Column(type="string",length=128,unique=true,nullable=false)
     */
    protected $slug;
    
    /**
     * @var bool
     * @Column(type="boolean",nullable=false)
     */
    protected $is_protected = false;
    
    // Relationships
    // ------------------------------------------------------------------
    
    /**
     * @var Collection
     * @ManyToMany(
     *     targetEntity="API\Entity\Users\User",
     *     mappedBy="groups"
     * )
     */
    protected $users;
    
    // Constructor/Destructor
    // ------------------------------------------------------------------
    
    /**
     * UserGroup constructor.
     */
    public function __construct()
    {
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
     * @return bool
     */
    public function getIsProtected(): bool
    {
        return $this->is_protected;
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
     * @param bool $is_protected
     * @return $this
     */
    public function setIsProtected( bool $is_protected )
    {
        $this->is_protected = $is_protected;
        return $this;
    }
    
    // Collection Managers
    // ------------------------------------------------------------------
    
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
