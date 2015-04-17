<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * 
 * @author vega
 *
 */
class Chambre {

	/**
	 * A reference to the hotel containing this room.
	 * 
	 * @var \AppBundle\Entity\HotelExpress
	 * 
	 * @ORM\ManyToOne(targetEntity="HotelExpress", inversedBy="chambres")
	 * @ORM\JoinColumn(name="hotel", referencedColumnName="ville")
	 * @ORM\Id
	 */
	protected $hotel;
	
	/**
	 * 
	 * The room number
	 * 
	 * @var integer
	 * 
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 */
	protected $numero;
	
	/**
	 * A reference to the night stays in thei room
	 * 
	 * @var \Doctrine\Common\Collections\Collection
	 * 
	 * @ORM\OneToMany(targetEntity="Nuitee", mappedBy="chambre")
	 */
	protected $nuitees;
	
    /**
     * Constructor
     * 
     * @param \AppBundle\Entity\HotelExpress $hotel
     * @param integer $numero
     */
    public function __construct(\AppBundle\Entity\HotelExpress $hotel, $numero) {
    	$this->hotel = $hotel;
        $this->numero = $numero;
        
        $this->hotel->addChambre($this);
    }
	
    /**
     * Get hotel
     *
     * @return \AppBundle\Entity\HotelExpress 
     */
    public function getHotel() {
        return $this->hotel;
    }
    
    /**
     * Get numero
     *
     * @return integer 
     */
    public function getNumero() {
        return $this->numero;
    }

        /**
     * Get chambres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getNuitees() {
        return $this->nuitees;
    }
    
    /**
     * Add chambres
     *
     * @param \AppBundle\Entity\Nuitee $nuitee
     * @return Chambre
     */
    public function addNuitee(\AppBundle\Entity\Nuitee $nuitee) {
    	
    	/*
    	 * Verify this room was the one used to create the night stay object
    	 */
    	if ($nuitee->getChambre() !== $this) {
    		throw new AccessDeniedHttpException("Trying to add stay to another room");
    	}
    	
        $this->nuitees[] = $nuitee;
        return $this;
    }
    
}