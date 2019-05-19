<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\RealizationRepository;
use App\Repository\UserRepository;
use App\Entity\Realization;
use App\Form\RealizationType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class RealizationController extends AbstractController
{

    /**
     * @var RealizationRepository
     * @var ObjectManager
     */

    private $repository;
    private $repositoryUser;
    private $objectManager;

    public function __construct(RealizationRepository $repository, UserRepository $repositoryUser, ObjectManager $objectManager)
    {
        $this->repository = $repository;
        $this->repositoryUser = $repositoryUser;
        $this->objectManager = $objectManager;

    }

    public function index()
    {
        $realizations = $this->repository->findAll();
        $user = $this->getUser();
        return $this->render('admin/realization/index.html.twig',
        [
            'user' => $user,
            'realizations' => $realizations,
            'current_menu' => 'realization',
        ]);
    }

    public function new(Request $request)
    {
        $realization = new Realization();
        $user = $this->getUser();
        $form = $this->createForm(RealizationType::class, $realization);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->persist($realization);
            $this->objectManager->flush();
            $this->addFlash('success','Réalisation crée avec succés');
            return $this->redirectToRoute('admin.realization.index');
        }

        return $this->render('admin/realization/new.html.twig',[
            'realization' => $realization,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'realization',
        ]);
    }

    public function edit(Realization $realization, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(RealizationType::class, $realization);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->flush();
            $this->addFlash('success','Réalisation modifiée avec succés');
            return $this->redirectToRoute('admin.realization.index');
        }

        return $this->render('admin/realization/edit.html.twig',[
            'realization' => $realization,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'realization',
        ]);
    }

    public function delete(Realization $realization, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $realization->getId(), $request->get('_token')))
        {
            $this->objectManager->remove($realization);
            $this->objectManager->flush();
            $this->addFlash('success','Réalisation supprimée avec succés');
        }
        return $this->redirectToRoute('admin.realization.index');
    }
}