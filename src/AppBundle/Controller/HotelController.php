<?php

namespace AppBundle\Controller;


use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;

use AppBundle\Entity\HotelExpress;
use AppBundle\Entity\Chambre;
use AppBundle\Entity\Nuitee;

use DateTime;

class HotelController extends Controller {
    
    /**
     * @Route("/hotel/", name="hotel.list")
     */
    public function hotels() {
		return $this->render('AppBundle:hotel:list.html.twig', array('hotels' => $this->getHotels()));

    }

       /**
     * @Route("/hotel/{ville}", name="hotel.detail")
     */
    public function hotel($ville) {
		return $this->render('AppBundle:hotel:detail.html.twig', array('hotel' =>$this->getHotel($ville)));
    }
	
    /**
     * @Route("/hotel/add/{ville}", name="hotel.add")
     */
    public function addHotel($ville) {
		$hotel = new HotelExpress($ville);
		$this->persist($hotel);
        
		return $this->render('default/message.html.twig', array(
			'message' => "Added hotel !".$hotel->getVille()
		));
		
    }

    /**
     * @Route("/hotel/remove/{ville}", name="hotel.remove")
     */
    public function removeHotel($ville) {
    	$hotel = $this->getHotel($ville);

    	if (!$hotel) {
			throw $this->createNotFoundException('No hotel found for city '.$ville);
		}    	
    	
		$this->remove($hotel);
		
		return $this->render('default/message.html.twig', array(
			'message' => "Removed hotel !".$hotel->getVille()
		));
		
    }
    
    /**
     * @Route("/hotel/add/{ville}/{numero}", name="hotel.add.room")
     */
    public function addRoom($ville,$numero) {

    	$hotel = $this->getHotel($ville);

    	if (!$hotel) {
			throw $this->createNotFoundException('No hotel found for city '.$ville);
		}    	
    	
		$room = new Chambre($hotel,$numero);
		$this->persist($room);
        
		return $this->render('default/message.html.twig', array(
			'message' => "Added hotel rooom :".$hotel->getVille()."<->".$room->getNumero()
		));
		
    }
    
        /**
     * @Route("/hotel/add/{ville}/{numero}/{date}", name="hotel.add.room.stay")
     */
    public function addRoomStay($ville,$numero,$date) {

    	$room = $this->getRoom($ville,$numero);

    	if (!$room) {
			throw $this->createNotFoundException('No room found for city '.$ville.' number '.$numero);
		}    	
    	
		$stay = new Nuitee($room,new DateTime($date));
		$this->persist($stay);
        
		return $this->render('default/message.html.twig', array(
			'message' => "Added hotel rooom stay at :".$stay->getChambre()->getHotel()->getVille()." ".$stay->getChambre()->getNumero()." =>  ".$stay->getDate()->format('Y-m-d')
		));
		
    }
    
    /**
     * Get the list of all the hotels.
     * 
     * @return \Doctrine\Common\Collections\Collection 
     */
    private function getHotels() {
		$em 	= $this->getDoctrine()->getManager();
    	return $em->getRepository('AppBundle:HotelExpress')->findAll();
    }
    
    /**
     * Get a hotel object givent its city
     * 
     * @param string $ville
     * @return \AppBundle\Entity\HotelExpress 
     */
    private function getHotel($ville) {

		$em 	= $this->getDoctrine()->getManager();
		return $em->getRepository('AppBundle:HotelExpress')->findOneByVille($ville);
    }

    /**
     * Get a room object givent its city and number
     * 
     * @param string $ville
     * @param integer $numero
     * @return \AppBundle\Entity\Chambre
     */
    private function getRoom($ville, $numero) {

		$em 	= $this->getDoctrine()->getManager();
		return $em->getRepository('AppBundle:Chambre')->findOneBy(array('hotel' => $ville, 'numero' => $numero));
    }
    
    /**
     * Adds an object to the managed persistent entities
     * 
     * @param unknown_type $entity
     */
    private function persist($entity) {
		$em = $this->getDoctrine()->getManager();
		$em-> persist($entity);
		$em->flush();
    }

   /**
     * Removes an object from thepersistent entities
     * 
     * @param unknown_type $entity
     */
    private function remove($entity) {
		$em = $this->getDoctrine()->getManager();
		$em-> remove($entity);
		$em->flush();
    }
    
    
}