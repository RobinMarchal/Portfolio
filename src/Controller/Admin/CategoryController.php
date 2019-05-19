<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Entity\Category;
use App\Form\CategoryType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class CategoryController extends AbstractController
{

    /**
     * @var CategoryRepository
     * @var ObjectManager
     */

    private $repository;
    private $repositoryUser;
    private $objectManager;

    public function __construct(CategoryRepository $repository, UserRepository $repositoryUser, ObjectManager $objectManager)
    {
        $this->repository = $repository;
        $this->repositoryUser = $repositoryUser;
        $this->objectManager = $objectManager;

    }

    public function index()
    {
        $categories = $this->repository->findAll();
        $user = $this->getUser();
        return $this->render('admin/categories/index.html.twig',
        [
            'user' => $user,
            'categories' => $categories,
            'current_menu' => 'category',
        ]);
    }

    public function new(Request $request)
    {
        $category = new Category();
        $user = $this->getUser();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->persist($category);
            $this->objectManager->flush();
            $this->addFlash('success','Categorie créé avec succés');
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/categories/new.html.twig',[
            'category' => $category,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'category',
        ]);
    }

    public function edit(Category $category, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(CategoryType::class, $category);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->flush();
            $this->addFlash('success','Catégorie modifiée avec succés');
            return $this->redirectToRoute('admin.category.index');
        }

        return $this->render('admin/categories/edit.html.twig',[
            'user' => $user,
            'category' => $category,
            'form' => $form->createView(),
            'current_menu' => 'category',
        ]);
    }

    public function delete(Category $category, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $category->getId(), $request->get('_token')))
        {
            $this->objectManager->remove($category);
            $this->objectManager->flush();
            $this->addFlash('success','Catégorie supprimée avec succés');
        }
        return $this->redirectToRoute('admin.category.index');
    }
}