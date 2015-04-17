<?php

namespace AppBundle\Entity;


use Doctrine\ORM\Mapping as ORM;

use DateTime;

/**
 * @ORM\Entity
 * 
 * @author vega
 *
 */
class Nuitee {

	/**
	 * This is a workaround to a limitation of Doctrine with primary keys that include composite
	 * foreign keys
	 * 
	 * WARNING This field should NEVER be used directly, use the attribute chambre instead.
	 * 
	 * @var String
	 * 
	 * @ORM\Column(type="string")
	 * @ORM\Id
	 */
	private $hotel;
	
	/**
	 * This is a workaround to a limitation of Doctrine with primary keys that include composite
	 * foreign keys
	 * 
	 * WARNING This field should NEVER be used directly, use the attribute chambre instead.
	 * 
	 * @var Integer
	 * 
	 * @ORM\Column(type="integer")
	 * @ORM\Id
	 */
	private $numero;
	
	/**
	 * A reference to the room of this night stay 
	 * 
	 * @var \AppBundle\Entity\HotelExpress
	 * 
	 * @ORM\ManyToOne(targetEntity="Chambre", inversedBy="nuitees")
	 * @ORM\JoinColumns({
	 * 		@ORM\JoinColumn(name="hotel", referencedColumnName="hotel"),
	 * 		@ORM\JoinColumn(name="numero", referencedColumnName="numero")
	 * })
	 * 
	 */
	protected $chambre;
	
	/**
	 * 
	 * The date of the night stay
	 * 
	 * @var String
	 * 
	 * @ORM\Column(type="string")
	 * @ORM\Id
	 */
	protected $date;
	
    /**
     * Constructor
     * 
     * @param \AppBundle\Entity\Chambre $chambre
     * @param DateTime $date
     */
    public function __construct(\AppBundle\Entity\Chambre $chambre, DateTime $date) {
    	$this->chambre 	= $chambre;
    	$this->hotel	= $chambre->getHotel()->getVille();
    	$this->numero	= $chambre->getNumero();
    	
    	/*
    	 * This is a workaround to a proble with Doctrine when using Dates as prmary keys
    	 */
        $this->date 	= $date->format('Y-m-d');
        
        $this->chambre->addNuitee($this);
    }
	
    /**
     * Get room
     *
     * @return \AppBundle\Entity\Chambre
     */
    public function getChambre() {
        return $this->chambre;
    }
    
    /**
     * Get date
     *
     * @return DateTime
     */
    public function getDate() {
        return new DateTime($this->date);
    }
    
}
