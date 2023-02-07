<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\ContactRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ContactController extends AbstractController
{
    private ContactRepository $contactRepository;

    public function __construct(ContactRepository $contactRepository)
    {
        $this->contactRepository = $contactRepository;
    }

    /**
     * @Route("/", name="showContacts")
     */
    public function show(Request $request, PaginatorInterface $paginator): Response
    {
        $contact = new Contact();
        $form = $this->createForm(ContactType::class, $contact);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->contactRepository->add($contact, true);

            return $this->redirectToRoute('showContacts');
        }

        $allContacts = $this->contactRepository->findAll();

        $pagination = $paginator->paginate(
            $allContacts,
            $request->query->getInt('page', 1),
            5
        );

        return $this->render('contact/index.html.twig', [
            'form' => $form->createView(),
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/delete/{id}", name="deleteContact")
     */
    public function delete(int $id): RedirectResponse
    {
        $contact = $this->contactRepository->find($id);

        if(!empty($contact)){
            $this->contactRepository->remove($contact, true);
        }

        return $this->redirectToRoute("showContacts");
    }

    /**
     * @Route("/{name}", name="updateContact")
     */
    public function update(Request $request): Response
    {
        $id = $request->get('id');
        $contact = $this->contactRepository->find($id);

        $form = $this->createForm(ContactType::class, $contact);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->contactRepository->add($contact, true);

            return $this->redirectToRoute("showContacts");
        }

        return $this->render('contact/updateForm.twig', [
            'form' => $form->createView(),
        ]);
    }
}
