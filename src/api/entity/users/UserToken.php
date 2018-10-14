<?php
namespace API\Entity\Users;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\Table;
use YX\Entity\BaseEntity;

/**
 * API\Entity\Users\UserToken
 * ----------------------------------------------------------------------
 * User access token entity, used to retrieve authentication and permissions
 * data for the user, with all the necessary data.
 *
 * @package     API\Entity\Users
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 *
 * @Entity
 * @Table(name="user_token")
 * @HasLifecycleCallbacks
 */
class UserToken extends BaseEntity
{
    // Properties
    // ------------------------------------------------------------------
    
    /**
     * @var string
     * @Column(type="text",length=4096,nullable=false)
     */
    protected $payload;
    
    /**
     * @var int
     * @Column(type="integer",nullable=false)
     */
    protected $expire_date;
    
    /**
     * @var bool
     * @Column(type="boolean",nullable=false)
     */
    protected $is_valid = true;
    
    // Relationships
    // ------------------------------------------------------------------
    
    /**
     * @var User
     * @ManyToOne(targetEntity="API\Entity\Users\User")
     * @JoinColumn(name="user_id",referencedColumnName="id")
     */
    protected $user;
    
    // Getters
    // ------------------------------------------------------------------
    
    /**
     * @return string
     */
    public function getPayload(): string
    {
        return $this->payload;
    }
    
    /**
     * @return int
     */
    public function getExpireDate(): int
    {
        return $this->expire_date;
    }
    
    /**
     * @return bool
     */
    public function getIsValid(): bool
    {
        return $this->is_valid;
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
     * @param string $payload
     * @return $this
     */
    public function setPayload( string $payload )
    {
        $this->payload = $payload;
        return $this;
    }
    
    /**
     * @param int $expire_date
     * @return $this
     */
    public function setExpireDate( int $expire_date )
    {
        $this->expire_date = $expire_date;
        return $this;
    }
    
    /**
     * @param bool $is_valid
     * @return $this
     */
    public function setIsValid( bool $is_valid )
    {
        $this->is_valid = $is_valid;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function toggleIsValid()
    {
        $this->is_valid = ! $this->is_valid;
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
