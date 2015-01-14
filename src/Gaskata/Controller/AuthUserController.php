<?php

namespace Gaskata\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Gaskata\Entity\AuthUser;
use Gaskata\Form\AuthUserType;

/**
 * AuthUser controller.
 *
 * @Route("/authuser")
 */
class AuthUserController extends Controller
{

    /**
     * Lists all AuthUser entities.
     *
     * @Route("/", name="authuser")
     * @Method("GET")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $entities = $em->getRepository('Gaskata:AuthUser')->findAll();

        return array(
            'entities' => $entities,
        );
    }
    /**
     * Creates a new AuthUser entity.
     *
     * @Route("/", name="authuser_create")
     * @Method("POST")
     * @Template("Gaskata:AuthUser:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new AuthUser();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('authuser_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a form to create a AuthUser entity.
     *
     * @param AuthUser $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(AuthUser $entity)
    {
        $form = $this->createForm(new AuthUserType(), $entity, array(
            'action' => $this->generateUrl('authuser_create'),
            'method' => 'POST',
        ));

        $form->add('submit', 'submit', array('label' => 'Create'));

        return $form;
    }

    /**
     * Displays a form to create a new AuthUser entity.
     *
     * @Route("/new", name="authuser_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new AuthUser();
        $form   = $this->createCreateForm($entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Finds and displays a AuthUser entity.
     *
     * @Route("/{id}", name="authuser_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Gaskata:AuthUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AuthUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to edit an existing AuthUser entity.
     *
     * @Route("/{id}/edit", name="authuser_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Gaskata:AuthUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AuthUser entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
    * Creates a form to edit a AuthUser entity.
    *
    * @param AuthUser $entity The entity
    *
    * @return \Symfony\Component\Form\Form The form
    */
    private function createEditForm(AuthUser $entity)
    {
        $form = $this->createForm(new AuthUserType(), $entity, array(
            'action' => $this->generateUrl('authuser_update', array('id' => $entity->getId())),
            'method' => 'PUT',
        ));

        $form->add('submit', 'submit', array('label' => 'Update'));

        return $form;
    }
    /**
     * Edits an existing AuthUser entity.
     *
     * @Route("/{id}", name="authuser_update")
     * @Method("PUT")
     * @Template("Gaskata:AuthUser:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('Gaskata:AuthUser')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find AuthUser entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('authuser_edit', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }
    /**
     * Deletes a AuthUser entity.
     *
     * @Route("/{id}", name="authuser_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('Gaskata:AuthUser')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find AuthUser entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('authuser'));
    }

    /**
     * Creates a form to delete a AuthUser entity by id.
     *
     * @param mixed $id The entity id
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('authuser_delete', array('id' => $id)))
            ->setMethod('DELETE')
            ->add('submit', 'submit', array('label' => 'Delete'))
            ->getForm()
        ;
    }
}
