<?php

namespace ContactBoxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use ContactBoxBundle\Entity\Person;

class PersonController extends Controller
{
    private function generateForm(Person $person)
    {
        return $this->createFormBuilder($person)
            ->add('name', 'text')
            ->add('surname', 'text')
            ->add('description', 'textarea')
            ->add('save', 'submit', array('label' => 'Add contact'))
            ->getForm();
    }

    /**
     * @Route("/new")
     * @Template()
     * @Method("GET")
     */
    public function personFormAction()
    {
        $newPerson = new Person();
        $form = $this->generateForm($newPerson);

        return array("form" => $form->createView());
    }

    /**
     * @Route("/new")
     * @Template("ContactBoxBundle:Person:showPerson.html.twig")
     * @Method("POST")
     */
    public function postPersonFormAction(Request $request)
    {
        $newPerson = new Person();
        $form = $this->generateForm($newPerson);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newPerson);
        $em->flush();

        return array('person' => $newPerson);
    }

    /**
     * @Route("/edit/{n}")
     * @Method("GET")
     */
    public function editPersonFormAction(Request $request, $n)
    {


    }

}
