<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class ArticleController extends AbstractController
{

    /**
     * @var ArticleRepository
     * @var ObjectManager
     */

    private $repository;
    private $repositoryUser;
    private $objectManager;

    public function __construct(ArticleRepository $repository, UserRepository $repositoryUser, ObjectManager $objectManager)
    {
        $this->repository = $repository;
        $this->repositoryUser = $repositoryUser;
        $this->objectManager = $objectManager;

    }

    public function index()
    {
        $articles = $this->repository->findAll();
        $user = $this->getUser();
        return $this->render('admin/articles/index.html.twig',
        [
            'user' => $user,
            'articles' => $articles,
            'current_menu' => 'article',
        ]);
    }

    public function new(Request $request)
    {
        $article = new Article();
        $user = $this->getUser();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->persist($article);
            $this->objectManager->flush();
            $this->addFlash('success','Article créé avec succés');
            return $this->redirectToRoute('admin.article.index');
        }

        return $this->render('admin/articles/new.html.twig',[
            'article' => $article,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'article',
        ]);
    }

    public function edit(Article $article, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->flush();
            $this->addFlash('success','Article modifié avec succés');
            return $this->redirectToRoute('admin.article.index');
        }

        return $this->render('admin/articles/edit.html.twig',[
            'article' => $article,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'article',
        ]);
    }
    public function delete(Article $article, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $article->getId(), $request->get('_token')))
        {
            $this->objectManager->remove($article);
            $this->objectManager->flush();
            $this->addFlash('success','Article supprimé avec succés');
        }
        return $this->redirectToRoute('admin.article.index');
    }
}