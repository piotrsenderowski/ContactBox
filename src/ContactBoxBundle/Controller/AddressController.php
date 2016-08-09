<?php

namespace ContactBoxBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;

use ContactBoxBundle\Entity\Address;
use ContactBoxBundle\Entity\Person;

class AddressController extends Controller
{
    /**
     * @Route("/{id}/addAddress")
     * @Template("ContactBoxBundle:Address:addAddress.html.twig")
     * @Method("GET")
     */
    public function addAddressAction(Request $request, $id)
    {
        $newAddress = new Address();
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Person");
        $person = $repository->find($id);
        $newAddress->setPerson($person);
        $form = $this->generateAddressForm($newAddress);

        return ['address_form' => $form->createView(), 'address' => $newAddress];
    }

    /**
     * @Route("/{id}/addAddress")
     * @Method("POST")
     */
    public function postAddAddressAction(Request $request, $id)
    {
        $newAddress = new Address();
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Person");
        $person = $repository->find($id);
        $newAddress->setPerson($person);
        $form = $this->generateAddressForm($newAddress);
        $form->handleRequest($request);

        $em = $this->getDoctrine()->getManager();
        $em->persist($newAddress);
        $em->flush();

        return $this->redirectToRoute("contactbox_person_showperson", ['id' => $id]);
    }

    /**
     * @Route("{id}/{addressId}/delete")
     * @Template()
     */
    public function deleteAddressAction($id, $addressId)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $this->getDoctrine()->getRepository("ContactBoxBundle:Address");
        $addressToDelete = $repository->find($addressId);

        $em->remove($addressToDelete);
        $em->flush();

        $response = $this->redirectToRoute("contactbox_person_showperson", ["id" => $id]);
        return $response;
    }


    private function generateAddressForm(Address $address)
    {
        return $this->createFormBuilder($address)
            ->add('city', 'text')
            ->add('street', 'text')
            ->add('house_number', 'text')
            ->add('apt_number', 'text', array('required' => false))
            ->add('address_type', 'text')
            ->add('save', 'submit', array('label' => 'Add address'))
            ->getForm();
    }
}
