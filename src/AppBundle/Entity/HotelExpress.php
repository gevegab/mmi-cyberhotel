<?php 

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * 
 * @ORM\Entity
 * @ORM\Table(name="hotel_express")
 * 
 * @author vega
 *
 */
class HotelExpress {
	
	/**
	 * The city where the hotel is located. 
	 *
	 * @var string
	 * 
	 * @ORM\Column(type="string", length=100)
	 * @ORM\Id
	 */
	protected $ville;

	/**
	 * The list of rooms in the hotel
	 * 
	 * @var \Doctrine\Common\Collections\Collection
	 * 
	 * @ORM\OneToMany(targetEntity="Chambre", mappedBy="hotel", cascade={"remove"})
	 */
	protected $chambres;

    /**
     * Constructor
     * 
     * @param string $ville
     */
    public function __construct($ville)
    {
    	$this->ville = $ville;
        $this->chambres = new \Doctrine\Common\Collections\ArrayCollection();
    }
	
    /**
     * Get ville
     *
     * @return string 
     */
    public function getVille() {
        return $this->ville;
    }

    /**
     * Get chambres
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getChambres() {
        return $this->chambres;
    }
    
    /**
     * Add chambres
     *
     * @param \AppBundle\Entity\Chambre $chambres
     * @return HotelExpress
     */
    public function addChambre(\AppBundle\Entity\Chambre $chambre) {
    	
    	/*
    	 * Verify this hotel was the one used to create the room object
    	 */
    	if ($chambre->getHotel() !== $this) {
    		throw new AccessDeniedHttpException("Trying to add room to another hotel");
    	}
    	
        $this->chambres[] = $chambre;
        return $this;
    }

}
