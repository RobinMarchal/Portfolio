<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\FormationRepository;
use App\Repository\UserRepository;
use App\Entity\Formation;
use App\Form\FormationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class FormationController extends AbstractController
{

    /**
     * @var FormationRepository
     * @var ObjectManager
     */

    private $repository;
    private $repositoryUser;
    private $objectManager;

    public function __construct(FormationRepository $repository, UserRepository $repositoryUser, ObjectManager $objectManager)
    {
        $this->repository = $repository;
        $this->repositoryUser = $repositoryUser;
        $this->objectManager = $objectManager;

    }

    public function index()
    {
        $user = $this->getUser();
        $formations = $this->repository->findAllDate();
        return $this->render('admin/formation/index.html.twig',
        [
            'user' => $user,
            'formations' => $formations,
            'current_menu' => 'formation',
        ]);
    }

    public function new(Request $request)
    {
        $formation = new Formation();
        $user = $this->getUser();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->persist($formation);
            $this->objectManager->flush();
            $this->addFlash('success','Formation créé avec succés');
            return $this->redirectToRoute('admin.formation.index');
        }

        return $this->render('admin/formation/new.html.twig',[
            'formation' => $formation,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'formation',
        ]);
    }

    public function edit(Formation $formation, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(FormationType::class, $formation);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->flush();
            $this->addFlash('success','Formation modifié avec succés');
            return $this->redirectToRoute('admin.formation.index');
        }

        return $this->render('admin/formation/edit.html.twig',[
            'formation' => $formation,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'formation',
        ]);
    }

    public function delete(Formation $formation, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $formation->getId(), $request->get('_token')))
        {
            $this->objectManager->remove($formation);
            $this->objectManager->flush();
            $this->addFlash('success','Formation supprimé avec succés');
        }
        return $this->redirectToRoute('admin.formation.index');
    }

}