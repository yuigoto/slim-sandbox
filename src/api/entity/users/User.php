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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\Table;
use YX\Entity\BaseProfileEntity;

/**
 * API\Entity\Users\User
 * ----------------------------------------------------------------------
 * User account entity.
 *
 * A user account is unique in name and e-mail address.
 *
 * It may have attributes attached to itself.
 *
 * It must have a role assigned to it, which tells which capabilities
 * this account has over the system (management, posts, reading, etc.).
 *
 * @package     API\Entity\Users
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 *
 * @Entity
 * @Table(name="user")
 * @HasLifecycleCallbacks
 */
class User extends BaseProfileEntity
{
    // Protected Properties
    // ------------------------------------------------------------------
    
    /**
     * @var string
     * @Column(type="string",length=128,unique=true,nullable=false)
     */
    protected $username;
    
    /**
     * @var string
     * @Column(type="string",length=255,unique=true,nullable=false)
     */
    protected $email;
    
    /**
     * @var bool
     * @Column(type="boolean",nullable=false)
     */
    protected $email_confirmed = false;
    
    /**
     * @var string
     * @Column(type="string",length=128,nullable=false)
     */
    protected $password;
    
    /**
     * @var bool
     * @Column(type="boolean",nullable=false)
     */
    protected $is_active = false;
    
    /**
     * @var bool
     * @Column(type="boolean",nullable=false)
     */
    protected $is_banned= false;
    
    /**
     * @var bool
     * @Column(type="boolean",nullable=false)
     */
    protected $is_locked = true;
    
    /**
     * @var bool
     * @Column(type="boolean",nullable=false)
     */
    protected $is_public = true;
    
    // Collections
    // ------------------------------------------------------------------
    
    /**
     * @var Collection
     * @OneToMany(
     *     targetEntity="API\Entity\Users\UserAttribute",
     *     mappedBy="user"
     * )
     */
    protected $attributes;
    
    /**
     * @var Collection
     * @ManyToMany(
     *     targetEntity="API\Entity\Users\UserGroup",
     *     inversedBy="users"
     * )
     * @JoinTable(
     *     name="user_group_collection",
     *     joinColumns={
     *          @JoinColumn(name="user_id",referencedColumnName="id")
     *     },
     *     inverseJoinColumns={
     *          @JoinColumn(name="group_id",referencedColumnName="id")
     *     }
     * )
     */
    protected $groups;
    
    /**
     * @var UserRole
     * @ManyToOne(
     *     targetEntity="API\Entity\Users\UserRole",
     *     inversedBy="users"
     * )
     * @JoinColumn(name="role_id",referencedColumnName="id")
     */
    protected $role;
    
    // Constructor/Destructor
    // ------------------------------------------------------------------
    
    /**
     * User constructor.
     */
    public function __construct()
    {
        $this->attributes = new ArrayCollection();
        $this->groups = new ArrayCollection();
    }
    
    // Getters
    // ------------------------------------------------------------------
    
    /**
     * @return string
     */
    public function getUsername(): string
    {
        return $this->username;
    }
    
    /**
     * @return string
     */
    public function getEmail(): string
    {
        return $this->email;
    }
    
    /**
     * @return bool
     */
    public function getEmailConfirmed(): bool
    {
        return $this->email_confirmed;
    }
    
    /**
     * @return string
     */
    public function getPassword(): string
    {
        return $this->password;
    }
    
    /**
     * @return bool
     */
    public function getIsActive(): bool
    {
        return $this->is_active;
    }
    
    /**
     * @return bool
     */
    public function getIsBanned(): bool
    {
        return $this->is_banned;
    }
    
    /**
     * @return bool
     */
    public function getIsLocked(): bool
    {
        return $this->is_locked;
    }
    
    /**
     * @return bool
     */
    public function getIsPublic(): bool
    {
        return $this->is_public;
    }
    
    /**
     * @return Collection
     */
    public function getAttributes(): Collection
    {
        return $this->attributes;
    }
    
    /**
     * @return UserRole
     */
    public function getRole(): UserRole
    {
        return $this->role;
    }
    
    // Setters
    // ------------------------------------------------------------------
    
    /**
     * @param string $username
     * @return $this;
     */
    public function setUsername( string $username )
    {
        if ( $this->username === null || $this->username === "" ) {
            $this->username = $username;
        }
        return $this;
    }
    
    /**
     * @param string $email
     * @return $this;
     */
    public function setEmail( string $email )
    {
        if (
            $this->email !== null
            && $this->email !== ""
            && $email !== $this->email
        ) {
            $this->setEmailConfirmed( false );
        }
        $this->email = $email;
        return $this;
    }
    
    /**
     * @param bool $email_confirmed
     * @return $this;
     */
    public function setEmailConfirmed( bool $email_confirmed )
    {
        $this->email_confirmed = $email_confirmed;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function toggleEmailConfirmed()
    {
        $this->email_confirmed = ! $this->email_confirmed;
        return $this;
    }
    
    /**
     * @param string $password
     * @return $this
     */
    public function setPassword( string $password )
    {
        $this->password = $password;
        return $this;
    }
    
    /**
     * @param bool $is_active
     * @return $this
     */
    public function setIsActive( bool $is_active )
    {
        $this->is_active = $is_active;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function toggleIsActive()
    {
        $this->is_active = ! $this->is_active;
        return $this;
    }
    
    /**
     * @param bool $is_banned
     * @return $this
     */
    public function setIsBanned( bool $is_banned )
    {
        $this->is_banned = $is_banned;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function toggleIsBanned()
    {
        $this->is_banned = ! $this->is_banned;
        return $this;
    }
    
    /**
     * @param bool $is_locked
     * @return $this
     */
    public function setIsLocked( bool $is_locked )
    {
        $this->is_locked = $is_locked;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function toggleIsLocked()
    {
        $this->is_locked = ! $this->is_locked;
        return $this;
    }
    
    /**
     * @param bool $is_public
     * @return $this
     */
    public function setIsPublic( bool $is_public )
    {
        $this->is_public = $is_public;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function toggleIsPublic()
    {
        $this->is_public = ! $this->is_public;
        return $this;
    }
    
    /**
     * @param UserRole $role
     */
    public function setRole( UserRole $role )
    {
        $this->role = $role;
    }
    
    // Collection Managers
    // ------------------------------------------------------------------
    
    /**
     * @param UserAttribute $attribute
     * @return $this
     */
    public function addAttribute( UserAttribute $attribute )
    {
        $this->attributes[] = $attribute;
        return $this;
    }
    
    /**
     * @param int $attribute_id
     * @return $this
     */
    public function removeAttribute( int $attribute_id )
    {
        /** @var UserAttribute $attribute */
        foreach ( $this->attributes as $attribute ) {
            if ( $attribute->getId() === $attribute_id ) {
                $this->attributes->removeElement( $attribute );
            }
        }
        return $this;
    }
    
    /**
     * @param UserGroup $group
     * @return $this
     */
    public function addGroup( UserGroup $group )
    {
        $this->groups[] = $group;
        return $this;
    }
    
    /**
     * @param int $group_id
     * @return $this
     */
    public function removeGroup( int $group_id )
    {
        /** @var UserGroup $group */
        foreach ( $this->groups as $group ) {
            if ( $group->getId() === $group_id ) {
                $this->groups->removeElement( $group );
            }
        }
        return $this;
    }
}
