<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TypeRepository;
use App\Repository\UserRepository;
use App\Entity\Type;
use App\Form\TypeType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class TypeController extends AbstractController
{

    /**
     * @var TypeRepository
     * @var ObjectManager
     */

    private $repository;
    private $repositoryUser;
    private $objectManager;

    public function __construct(TypeRepository $repository, UserRepository $repositoryUser, ObjectManager $objectManager)
    {
        $this->repository = $repository;
        $this->repositoryUser = $repositoryUser;
        $this->objectManager = $objectManager;

    }

    public function index()
    {
        $types = $this->repository->findAll();
        $user = $this->getUser();
        return $this->render('admin/types/index.html.twig',
        [
            'user' => $user,
            'types' => $types,
            'current_menu' => 'type',
        ]);
    }

    public function new(Request $request)
    {
        $type = new Type();
        $user = $this->getUser();
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->persist($type);
            $this->objectManager->flush();
            $this->addFlash('success','Type créé avec succés');
            return $this->redirectToRoute('admin.type.index');
        }

        return $this->render('admin/types/new.html.twig',[
            'type' => $type,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'type',
        ]);
    }

    public function edit(Type $type, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(TypeType::class, $type);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->flush();
            $this->addFlash('success','Type modifié avec succés');
            return $this->redirectToRoute('admin.type.index');
        }

        return $this->render('admin/types/edit.html.twig',[
            'type' => $type,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'type',
        ]);
    }

    public function delete(Type $type, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $type->getId(), $request->get('_token')))
        {
            $this->objectManager->remove($type);
            $this->objectManager->flush();
            $this->addFlash('success','Type supprimé avec succés');
        }
        return $this->redirectToRoute('admin.type.index');
    }
}