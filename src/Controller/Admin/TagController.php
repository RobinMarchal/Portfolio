<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use App\Entity\Tag;
use App\Form\TagType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;

class TagController extends AbstractController
{

    /**
     * @var TagRepository
     * @var ObjectManager
     */

    private $repository;
    private $repositoryUser;
    private $objectManager;

    public function __construct(TagRepository $repository, UserRepository $repositoryUser, ObjectManager $objectManager)
    {
        $this->repository = $repository;
        $this->repositoryUser = $repositoryUser;
        $this->objectManager = $objectManager;

    }

    public function index()
    {
        $tags = $this->repository->findAll();
        $user = $this->getUser();
        return $this->render('admin/tags/index.html.twig',
        [
            'user' => $user,
            'tags' => $tags,
            'current_menu' => 'tag',
        ]);
    }

    public function new(Request $request)
    {
        $tag = new Tag();
        $user = $this->getUser();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->persist($tag);
            $this->objectManager->flush();
            $this->addFlash('success','Tag créé avec succés');
            return $this->redirectToRoute('admin.tag.index');
        }

        return $this->render('admin/tags/new.html.twig',[
            'tag' => $tag,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'tag',
        ]);
    }

    public function edit(Tag $tag, Request $request)
    {
        $user = $this->getUser();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $this->objectManager->flush();
            $this->addFlash('success','Tag modifié avec succés');
            return $this->redirectToRoute('admin.tag.index');
        }

        return $this->render('admin/tags/edit.html.twig',[
            'tag' => $tag,
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'tag',
        ]);
    }

    public function delete(Tag $tag, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $tag->getId(), $request->get('_token')))
        {
            $this->objectManager->remove($tag);
            $this->objectManager->flush();
            $this->addFlash('success','Tag supprimé avec succés');
        }
        return $this->redirectToRoute('admin.tag.index');
    }
}