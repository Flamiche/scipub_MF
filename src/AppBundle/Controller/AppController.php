<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Publication;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class AppController
 * @package AppBundle\Controller
 */
class AppController extends Controller
{
    /**
     * Home page action.
     *
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function homeAction()
    {
        $latestPub = $this->getDoctrine()
            ->getRepository('AppBundle:Publication')
            ->findBy([], ['publishedAt' => 'DESC'], 3);

        return $this->render('AppBundle:App:home.html.twig', [
            'publications' => $latestPub,
        ]);
    }

    public function publicationAction($publicationId)
    {
    	$em = $this->getDoctrine()->getManager();

    	$publication = $em
    			->getRepository('AppBundle:Publication')
    			->find($publicationId);

    	return $this->render('AppBundle:App:publication.html.twig', array(
    		'publication' => $publication,
    		));
    }

    public function sciencesAction()
    {
    	$em = $this->getDoctrine()->getManager();

    	$sciences = $em
    			->getRepository('AppBundle:Science')
    			->findAll(['title' => 'ASC']);

    	return $this->render('AppBundle:App:science.html.twig', array(
    		'sciences' => $sciences,
    		));
    }

    public function sciencesDetailAction($scienceId)
    {
    	$em = $this->getDoctrine()->getManager();

    	$science = $em
    			->getRepository('AppBundle:Science')
    			->find($scienceId);   

        $publications = $em
            	->getRepository('AppBundle:Publication')
           		->findBy(['science' => $science]);   	 	

    	return $this->render('AppBundle:App:science_detail.html.twig', array(
    		'science' => $science,
    		'publications' => $publications,
    		));
    }

    public function publishAction()
    {
    	$publication = new Publication(); 
        $form = $this
                ->createForm('AppBundle\Form\PublicationType', $publication, [
                	'admin_mode' => false,
                	]);

        return $this->render('AppBundle:App:publish.html.twig', [
        	'form' => $form->createView()
        	]);
    }
}
