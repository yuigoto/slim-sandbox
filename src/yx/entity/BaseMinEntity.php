<?php
namespace YX\Entity;

use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use YX\Core\Mappable;

/**
 * YX\Entity\BaseMinEntity
 * ----------------------------------------------------------------------
 * Minimum base entity.
 *
 * Provides only three fields:
 * - `id`, a numeric primary key, required by Doctrine;
 * - `created_at`, the creation date timestamp;
 * - `updated_at`, the update date timestamp;
 *
 * Inherits its serializable properties from `YX\Core\Mappable`.
 *
 * The idea behind these base type entities is to avoid declaring long
 * entities by breaking them and chain-extending them.
 *
 * @package     YX\Entity
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class BaseMinEntity extends Mappable
{
    // Protected Properties
    // ------------------------------------------------------------------
    
    /**
     * Primary key, numeric and unique.
     *
     * @var int
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;
    
    /**
     * Entry creation date.
     *
     * @var string
     * @Column(type="datetime")
     */
    protected $created_at;
    
    /**
     * Entry update date.
     *
     * @var string
     * @Column(type="datetime")
     */
    protected $updated_at;
    
    // Getters
    // ------------------------------------------------------------------
    
    /**
     * Retrieves the ID.
     *
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }
    
    /**
     * Retrieves creation date.
     *
     * @return string
     */
    public function getCreatedAt(): string
    {
        return $this->created_at;
    }
    
    /**
     * Retrieves update date.
     *
     * @return string
     */
    public function getUpdatedAt(): string
    {
        return $this->updated_at;
    }
    
    // Protected Setters
    // ------------------------------------------------------------------
    
    /**
     * Sets creation date.
     *
     * @param \DateTime $created_at
     * @return $this
     */
    public function setCreatedAt( \DateTime $created_at )
    {
        $this->created_at = $created_at;
        return $this;
    }
    
    /**
     * Sets update date.
     *
     * @param \DateTime $updated_at
     * @return $this
     */
    public function setUpdatedAt( \DateTime $updated_at )
    {
        $this->updated_at = $updated_at;
        return $this;
    }
    
    // Lifecycle Callbacks
    // ------------------------------------------------------------------
    
    /**
     * Sets the creation and update.
     *
     * @return void
     * @PrePersist
     * @PreUpdate
     */
    public function updateTimestamps()
    {
        $this->setUpdatedAt( new \DateTime( 'now' ) );
        if ( $this->getCreatedAt() === null ) {
            $this->setCreatedAt( new \DateTime( 'now' ) );
        }
    }
}
