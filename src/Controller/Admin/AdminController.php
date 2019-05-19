<?php
namespace App\Controller\Admin;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\UserRepository;

class AdminController extends AbstractController
{
    /**
     * @var UserRepository
    */

    private $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;

    }

    public function index()
    {
        $user = $this->getUser();
        return $this->render('admin/index.html.twig', 
        [
            'user' => $user,
            'current_menu' => 'admin',
        ]
    );
    }
}