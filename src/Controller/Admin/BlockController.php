<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\BlockRepository;
use App\Repository\UserRepository;
use App\Entity\Block;
use App\Form\BlockType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class BlockController extends AbstractController
{

    /**
     * @var BlockRepository
     * @var ObjectManager
     */

    private $repository;
    private $repositoryUser;
    private $objectManager;

    public function __construct(BlockRepository $repository, UserRepository $repositoryUser, ObjectManager $objectManager)
    {
        $this->repository = $repository;
        $this->repositoryUser = $repositoryUser;
        $this->objectManager = $objectManager;

    }

    public function index()
    {
        $blocks = $this->repository->findAllBinder();
        $user = $this->getUser();
        return $this->render('admin/blocks/index.html.twig',
        [
            'user' => $user,
            'blocks' => $blocks,
            'current_menu' => 'block',
        ]);
    }

    public function new(Request $request)
    {
        $block = new Block();
        $user = $this->getUser();
        $form = $this->createForm(BlockType::class, $block);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->persist($block);
            $this->objectManager->flush();
            $this->addFlash('success','Bloc créé avec succés');
            return $this->redirectToRoute('admin.block.index');
        }

        return $this->render('admin/blocks/new.html.twig',[
            'block' => $block,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'block',
        ]);
    }

    public function edit(Block $block, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(BlockType::class, $block);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->flush();
            $this->addFlash('success','Bloc modifié avec succés');
            return $this->redirectToRoute('admin.block.index');
        }

        return $this->render('admin/blocks/edit.html.twig',[
            'block' => $block,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'block',
        ]);
    }

    public function delete(Block $block, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $block->getId(), $request->get('_token')))
        {
            $this->objectManager->remove($block);
            $this->objectManager->flush();
            $this->addFlash('success','Bloc supprimé avec succés');
        }
        return $this->redirectToRoute('admin.block.index');
    }
}