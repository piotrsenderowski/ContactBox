<?php

namespace ContactBoxBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use ContactBoxBundle\Entity\Phone;
use ContactBoxBundle\Entity\Person;
use Symfony\Component\HttpFoundation\Request;

class PhoneController extends Controller
{

    /**
     * @Route("/{id}/addPhone")
     * @Template("ContactBoxBundle:Phone:addPhone.html.twig")
     * @Method("GET")
     */
    public function addPhoneAction(Request $request, $id)
    {
        $newPhone = new Phone();
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Person");
        $person = $repository->find($id);
        $newPhone->setPerson($person);
        $form = $this->generatePhoneForm($newPhone);

        return ['phone_form' => $form->createView(), 'phone' => $newPhone];
    }

    /**
     * @Route("/{id}/addPhone")
     * @Method("POST")
     */
    public function postAddPhoneAction(Request $request, $id)
    {
        $newPhone = new Phone();
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Person");
        $person = $repository->find($id);
        $newPhone->setPerson($person);
        $form = $this->generatePhoneForm($newPhone);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newPhone);
        $em->flush();

        return $this->redirectToRoute("contactbox_person_showperson", ['id' => $id]);
    }

    /**
     * @Route("/{id}/deletePhone")
     * @Method("GET")
     */
    public function deletePhoneAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository= $this->getDoctrine()->getRepository("ContactBoxBundle:Phone");
        $phoneToDelete = $repository->find($id);
        $personId = $phoneToDelete->getPerson()->getId();

        $em->remove($phoneToDelete);
        $em->flush();
        $response = $this->redirectToRoute("contactbox_person_showperson", ['id' => $personId]);
        return $response;

    }


    private function generatePhoneForm(Phone $phone)
    {
        return $this->createFormBuilder($phone)
            ->add('phoneNumber', 'text')
            ->add('phoneType', 'text')
            ->add('save', 'submit', ['label' => 'Add phone'])
            ->getForm();
    }
}
