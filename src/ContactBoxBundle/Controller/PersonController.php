<?php

namespace ContactBoxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use ContactBoxBundle\Entity\Person;
use ContactBoxBundle\Entity\Address;

class PersonController extends Controller
{
    /**
     * @Route("/")
     * @Method("GET")
     * @Template("ContactBoxBundle:Person:listPerson.html.twig")
     */
    public function listPersonAction()
    {
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Person");
        $allPersons = $repository->findAllSurnameAsc();
        return array('allPersons' => $allPersons);
    }

    /**
     * @Route("/new")
     * @Template("ContactBoxBundle:Person:personForm.html.twig")
     * @Method("GET")
     */
    public function newPersonAction()
    {
        $newPerson = new Person();
        $form = $this->generatePersonForm($newPerson);

        return ["person_form" => $form->createView()];
    }

    /**
     * @Route("/new")
     * @Template("ContactBoxBundle:Person:showPerson.html.twig")
     * @Method("POST")
     */
    public function postNewPersonAction(Request $request)
    {
        $newPerson = new Person();
        $form = $this->generatePersonForm($newPerson);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newPerson);
        $em->flush();

        return ['person' => $newPerson];
    }

    /**
     * @Route("/{id}/modify")
     * @Method("GET")
     * @Template("ContactBoxBundle:Person:personForm.html.twig")
     */
    public function modifyPersonAction($id)
    {
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Person");
        $editPerson = $repository->find($id);

        $personForm = $this->generatePersonForm($editPerson);

        return ['person_form' => $personForm->createView()];
    }

    /**
     * @Route("/{id}/modify")
     * @Method("POST")
     * @Template("ContactBoxBundle:Person:showPerson.html.twig")
     */
    public function postModifyPersonAction(Request $request, $id)
    {
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Person");
        $editPerson = $repository->find($id);

        $form = $this->generatePersonForm($editPerson);
        $form->handleRequest($request);

        $this->getDoctrine()->getManager()->flush();

        return ['person' => $editPerson];
    }

    /**
     * @Route("/{id}/delete")
     * @Method("GET")
     */
    public function deletePersonAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Person");
        $personToDelete = $repository->find($id);

        $em->remove($personToDelete);
        $em->flush();

        $response = $this->redirectToRoute("contactbox_person_listperson");
        return $response;
    }

    /**
     * @Route("/{id}")
     * @Method("GET")
     * @Template()
     */
    public function showPersonAction($id)
    {
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Person");
        $person = $repository->find($id);
        $addresses = $person->getAddresses();
        $emails = $person->getEmails();
        $phones = $person->getPhones();

        if ($person === null) {
            return $this->redirectToRoute("contactbox_person_listperson");
        }

        return ['person' => $person, 'allAddresses'=> $addresses, 'allEmails' => $emails, 'allPhones' => $phones];
    }

    private function generatePersonForm(Person $person)
    {
        return $this->createFormBuilder($person)
            ->add('name', 'text')
            ->add('surname', 'text')
            ->add('description', 'textarea')
            ->add('save', 'submit', array('label' => 'Send'))
            ->getForm();
    }

}
