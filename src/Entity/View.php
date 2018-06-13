<?php

namespace BOF\Entity;

/**
 * @Entity
 * @Table(name="views")
 **/
class View
{
    /** 
     * @Id
     *  @Column(type="integer")
     *  @GeneratedValue 
    **/
    protected $id;

    /**
     * @Column(type="date")
     */
    protected $date;

    /**
     * @Column(type="integer")
     */
    protected $views;

    /**
     * @ManyToOne(targetEntity="Profile", inversedBy="views")
     * @JoinColumn(name="profile_id", referencedColumnName="profile_id")
     */
    protected $profile;


    public function getProfileId()
    {
        return $this->profile_id;
    }

    public function getDate()
    {
        return $this->date;
    }

    public function getViews()
    {
        return $this->views;
    }
}