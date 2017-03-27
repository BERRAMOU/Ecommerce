<?php

namespace Ecommerce\EcommerceBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Ecommerce\EcommerceBundle\Entity\Categories;
use Ecommerce\EcommerceBundle\Form\CategoriesType;

/**
 * Categories controller.
 *
 */
class CategoriesAdminController extends Controller
{
    /**
     * Lists all Categories entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $categories = $em->getRepository('EcommerceBundle:Categories')->findAll();

        return $this->render('EcommerceBundle:Administration:categories/layout/index.html.twig', array(
            'categories' => $categories,
        ));
    }

    /**
     * Creates a new Categories entity.
     *
     */
    public function newAction(Request $request)
    {
        $category = new Categories();
        $form = $this->createForm('Ecommerce\EcommerceBundle\Form\CategoriesType', $category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('categories_show', array('id' => $category->getId()));
        }

        return $this->render('EcommerceBundle:Administration:categories/layout/new.html.twig', array(
            'category' => $category,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a Categories entity.
     *
     */
    public function showAction(Categories $category)
    {
        $deleteForm = $this->createDeleteForm($category);

        return $this->render('EcommerceBundle:Administration:categories/layout/show.html.twig', array(
            'category' => $category,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing Categories entity.
     *
     */
    public function editAction(Request $request, Categories $category)
    {
        $deleteForm = $this->createDeleteForm($category);
        $editForm = $this->createForm('Ecommerce\EcommerceBundle\Form\CategoriesType', $category);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($category);
            $em->flush();

            return $this->redirectToRoute('categories_edit', array('id' => $category->getId()));
        }

        return $this->render('EcommerceBundle:Administration:categories/layout/edit.html.twig', array(
            'category' => $category,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Categories entity.
     *
     */
    public function deleteAction(Request $request, Categories $category)
    {
        $form = $this->createDeleteForm($category);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($category);
            $em->flush();
        }

        return $this->redirectToRoute('categories_index');
    }

    /**
     * Creates a form to delete a Categories entity.
     *
     * @param Categories $category The Categories entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Categories $category)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('categories_delete', array('id' => $category->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
