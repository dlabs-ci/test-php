<?php

namespace BOF\Entity;

use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass="BOF\Repository\ProfileRepository")
 * @Table(name="profiles")
 **/
class Profile
{
    /** 
     * @Id
     *  @Column(type="integer")
     *  @GeneratedValue 
    **/
    protected $profile_id;

    /**
     *  @Column(type="string") 
    **/
    protected $profile_name;

    /**
     * @OneToMany(targetEntity="View", mappedBy="profile")
     */
    protected $views;

    /**
     * Construct method of this entity
     */
    public function __construct()
    {
        $this->views = new ArrayCollection();
    }

    public function getProfileId()
    {
        return $this->profile_id;
    }

    public function getViews()
    {
        return $this->views;
    }
}