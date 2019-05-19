<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\BlockRepository;
use App\Entity\Block;
use App\Repository\FormationRepository;
use App\Entity\Formation;
use App\Repository\CategoryRepository;
use App\Repository\RealizationRepository;
use App\Entity\Realization;
use App\Repository\ArticleRepository;
use App\Entity\Article;

class HomeController extends AbstractController
{
    /**
     * @var BlockRepository
     */

    private $repository;
    private $repositoryForm;
    private $repositoryRea;
    private $repositoryArt;

    public function __construct(BlockRepository $repository, FormationRepository $repositoryForm, RealizationRepository $repositoryRea, ArticleRepository $repositoryArt)
    {
        $this->repository = $repository;
        $this->repositoryForm = $repositoryForm;
        $this->repositoryRea = $repositoryRea;
        $this->repositoryArt = $repositoryArt;
    }

    public function index(): Response
    {
        $block = $this->repository->findOnlineBinder();

        return $this->render('index/home/index.html.twig',
        [
            'current_menu' => 'home',
            'presentation' => 'Présentation',
            'content' => 'Contenu',
            'parallax' => 'Parallax',   
            'blocks' => $block,
        ]);
    }

    public function legalnotice(): Response
    {
        return $this->render('index/home/legalnotice.html.twig');
    }

    public function formation(): Response
    {       
        $block = $this->repository->findOnlineBinder();
        $formation = $this->repositoryForm->findOnline();

        return $this->render('index/formation/index.html.twig',
        [
            'current_menu' => 'formation', 
            'formations' => $formation,
            'format' => 'Formation',
            'content' => 'Contenu',
            'parallax' => 'Parallax', 
            'blocks' => $block,
        ]);
    }

    public function competence(): Response
    {       
        $block = $this->repository->findOnlineBinder();

        return $this->render('index/competence/index.html.twig',
        [
            'current_menu' => 'competence',
            'comp' => 'Compétences',
            'content' => 'Contenu',
            'parallax' => 'Parallax',
            'grid3' => 'Colonnes 3',
            'blocks' => $block,
        ]);
    }

    public function realization(): Response
    {       
        $realization = $this->repositoryRea->findOnline();

        return $this->render('index/realization/index.html.twig',
        [
            'current_menu' => 'realization',
            'realizations' => $realization,
        ]);
    }
    public function realizationShow(Realization $realization,string $slug): Response
    {
        if ($realization->getSlug() !== $slug)
        {
            return $this->redirectToRoute('realization.show',
            [
                'id' => $realization->getId(),
                'slug' => $realization->getSlug()
            ], 301);
        }

        return $this->render('index/realization/show.html.twig',
        [
            'current_menu' => 'realization',
            'realization' => $realization,
        ]);
    }

    public function article(): Response
    {       
        $article = $this->repositoryArt->findOnline();

        return $this->render('index/article/index.html.twig',
        [
            'current_menu' => 'article',
            'articles' => $article,
        ]);
    }

    public function articleShow(Article $article,string $slug): Response
    {
        if ($article->getSlug() !== $slug)
        {
            return $this->redirectToRoute('article.show',
            [
                'id' => $article->getId(),
                'slug' => $article->getSlug()
            ], 301);
        }

        return $this->render('index/article/show.html.twig',
        [
            'current_menu' => 'article',
            'article' => $article,
        ]);
    }
    
}