<?php

namespace ContactBoxBundle\Controller;

use ContactBoxBundle\Entity\Email;
use ContactBoxBundle\Entity\Person;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

class EmailController extends Controller
{

    /**
     * @Route("/{id}/addEmail")
     * @Template("ContactBoxBundle:Email:addEmail.html.twig")
     * @Method("GET")
     */
    public function addEmailAction(Request $request, $id)
    {
        $newEmail = new Email();
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Person");
        $person = $repository->find($id);
        $newEmail->setPerson($person);
        $form = $this->generateEmailForm($newEmail);

        return ['email_form' => $form->createView(), 'email' => $newEmail];
    }

    /**
     * @Route("/{id}/addEmail")
     * @Method("POST")
     */
    public function postAddEmailAction(Request $request, $id)
    {
        $newEmail = new Email();
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Person");
        $person = $repository->find($id);
        $newEmail->setPerson($person);
        $form = $this->generateEmailForm($newEmail);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newEmail);
        $em->flush();

        return $this->redirectToRoute("contactbox_person_showperson", ['id' => $id]);
    }

    /**
     * @Route("/{id}/deleteEmail")
     * @Method("GET")
     */
    public function deleteEmailAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository= $this->getDoctrine()->getRepository("ContactBoxBundle:Email");
        $emailToDelete = $repository->find($id);
        $personId = $emailToDelete->getPerson()->getId();

        $em->remove($emailToDelete);
        $em->flush();
        $response = $this->redirectToRoute("contactbox_person_showperson", ['id' => $personId]);
        return $response;

    }


    private function generateEmailForm(Email $email)
    {
        return $this->createFormBuilder($email)
            ->add('email', 'text')
            ->add('emailType', 'text')
            ->add('save', 'submit', ['label' => 'Add email'])
            ->getForm();
    }
}
