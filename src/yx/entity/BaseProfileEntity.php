<?php
namespace YX\Entity;

use Doctrine\ORM\Mapping\Column;

/**
 * YX\Entity\BaseProfileEntity
 * ----------------------------------------------------------------------
 * Provides an extended version of `BaseEntity`, with additional fields
 * targeted towards profile-type entities.
 *
 * Besides the base entity fields, it also declares:
 * - An `image` and `thumb` fields to store avatars;
 * - Short and long description fields for text data;
 *
 * The idea behind these base type entities is to avoid declaring long
 * entities by breaking them and chain-extending them.
 *
 * @package     YX\Entity
 * @author      Fabio Y. Goto <lab@yuiti.com.br>
 * @since       0.0.1
 */
class BaseProfileEntity extends BaseEntity
{
    // Protected Properties
    // ------------------------------------------------------------------
    
    /**
     * Image file name.
     *
     * This file must be stored in the entity's specific folder, inside the
     * public upload directory.
     *
     * @var string
     * @Column(type="string",length=128,nullable=true)
     */
    protected $image;
    
    /**
     * Image thumb file name.
     *
     * Usually saved in the same folder as `$image`, but with a suffix, like
     * `_mini`, to distinguish the file.
     *
     * @var string
     * @Column(type="string",length=128,nullable=true)
     */
    protected $thumb;
    
    /**
     * Short description for the entity.
     *
     * @var string
     * @Column(type="string",length=255,nullable=true)
     */
    protected $short_description;
    
    /**
     * Long description for the entity.
     *
     * @var string
     * @Column(type="text",nullable=true)
     */
    protected $description;
    
    // Getters
    // ------------------------------------------------------------------
    
    /**
     * Retrieves the image file name, without folder structure.
     *
     * @return string
     */
    public function getImage(): string
    {
        return $this->image;
    }
    
    /**
     * Retrieves the image thumbnail file name, without the folder structure.
     *
     * @return string
     */
    public function getThumb(): string
    {
        return $this->thumb;
    }
    
    /**
     * Retrieves the entity's short description.
     *
     * @return string
     */
    public function getShortDescription(): string
    {
        return $this->short_description;
    }
    
    /**
     * Retrieves the entity's long description.
     *
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }
    
    // Setters
    // ------------------------------------------------------------------
    
    /**
     * Sets the entity's image file name.
     *
     * @param string $image
     * @return $this
     */
    public function setImage( string $image )
    {
        $this->image = $image;
        return $this;
    }
    
    /**
     * Sets the entity's image thumbnail file name.
     *
     * @param string $thumb
     * @return $this
     */
    public function setThumb( string $thumb )
    {
        $this->thumb = $thumb;
        return $this;
    }
    
    /**
     * Sets the entity's short description.
     *
     * @param string $short_description
     * @return $this
     */
    public function setShortDescription( string $short_description )
    {
        $this->short_description = $short_description;
        return $this;
    }
    
    /**
     * Sets the entity's long description.
     *
     * @param string $description
     * @return $this
     */
    public function setDescription( string $description )
    {
        $this->description = $description;
        return $this;
    }
}
