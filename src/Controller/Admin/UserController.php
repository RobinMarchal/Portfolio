<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{

    /**
     * @var CategoryRepository
     * @var ObjectManager
     */

    private $repository;
    private $objectManager;
    private $encoder;

    public function __construct(UserRepository $repository, ObjectManager $objectManager, UserPasswordEncoderInterface $encoder)
    {
        $this->repository = $repository;
        $this->objectManager = $objectManager;
        $this->encoder = $encoder;
    }

    public function index()
    {
        $users = $this->repository->findAll();
        $user = $this->getUser();
        return $this->render('admin/users/index.html.twig',
        [
            'user' => $user,
            'users' => $users,
            'current_menu' => 'user',
        ]);
    }

    public function new(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $users = new User();
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $users);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $users->setPassword(
                $passwordEncoder->encodePassword(
                    $users,
                    $form->get('password')->getData()
                )
            );

            $this->objectManager->persist($users);
            $this->objectManager->flush();
            $this->addFlash('success','Utilisateur créé avec succés');
            return $this->redirectToRoute('admin.user.index');
        }

        return $this->render('admin/users/new.html.twig',[
            'user' => $user,
            'form' => $form->createView(),
            'current_menu' => 'user',
        ]);
    }

    public function delete(User $user, Request $request)
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->get('_token')))
        {
            $this->objectManager->remove($user);
            $this->objectManager->flush();
            $this->addFlash('success','Utilisateur supprimé avec succés');
        }
        return $this->redirectToRoute('admin.user.index');
    }

}