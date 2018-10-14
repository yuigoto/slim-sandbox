<?php
namespace API\Entity\Users;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\Table;
use Ramsey\Uuid\Uuid;
use YX\Entity\BaseEntity;

/**
 * API\Entity\Users\UserActivationRequest
 * ----------------------------------------------------------------------
 * Represents validation/authentication keys to:
 *
 * - Activate a user account;
 * - Confirm and activate an e-mail address;
 * - Change/recover password for accounts;
 *
 * @package     API\Entity\Users
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 *
 * @Entity
 * @Table(name="user_activation_request")
 * @HasLifecycleCallbacks
 */
class UserActivationRequest extends BaseEntity
{
    // Protected Properties
    // ------------------------------------------------------------------
    
    /**
     * @var string
     * @Column(type="string",length=128,nullable=false)
     */
    protected $auth_key;
    
    /**
     * One of:
     * - activation;
     * - email_confirmation;
     * - new_password;
     *
     * @var string
     * @Column(type="string",length=128,nullable=false)
     */
    protected $request_type = "activation";
    
    /**
     * @var string
     * @Column(type="datetime",nullable=true)
     */
    protected $expire_date;
    
    /**
     * @var bool
     * @Column(type="boolean",nullable=false)
     */
    protected $is_used = false;
    
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
    public function getAuthKey(): string
    {
        return $this->auth_key;
    }
    
    /**
     * @return string
     */
    public function getRequestType(): string
    {
        return $this->request_type;
    }
    
    /**
     * @return string
     */
    public function getExpireDate(): string
    {
        return $this->expire_date;
    }
    
    /**
     * @return bool
     */
    public function getIsUsed(): bool
    {
        return $this->is_used;
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
     * @param string $request_type
     * @return $this
     */
    public function setRequestType( string $request_type )
    {
        $this->request_type = $request_type;
        return $this;
    }
    
    /**
     * @param \DateTime $expire_date
     * @return $this
     */
    public function setExpireDate( \DateTime $expire_date )
    {
        $this->expire_date = $expire_date;
        return $this;
    }
    
    /**
     * @param bool $is_used
     * @return $this
     */
    public function setIsUsed( bool $is_used )
    {
        $this->is_used = $is_used;
        return $this;
    }
    
    /**
     * @return $this
     */
    public function toggleIsUsed()
    {
        $this->is_used = ! $this->is_used;
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
    
    // Lifecycle Callbacks
    // ------------------------------------------------------------------
    
    /**
     * @param string $auth_key
     * @return $this
     */
    protected function setAuthKey (string $auth_key )
    {
        $this->auth_key = $auth_key;
        return $this;
    }
    
    /**
     * @PrePersist
     */
    public function generateAuthKey()
    {
        try {
            $uuid = Uuid::uuid4();
            
            if ( ! $uuid || $uuid === null || $uuid === false) {
                throw new \Exception( "Request auth key generation error." );
            } else {
                $this->setAuthKey(
                    $uuid->toString()
                );
            }
        } catch ( \Exception $e ) {
            return false;
        }
    }
}