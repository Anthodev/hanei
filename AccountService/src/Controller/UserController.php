<?php

namespace App\Controller;

use Exception;
use App\Document\Role;
use App\Document\User;
use JMS\Serializer\SerializerInterface;
use Doctrine\ODM\MongoDB\DocumentManager;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $userRepo;
    private $roleRepo;
    private $dm;
    private $serializer;

    public function __construct(DocumentManager $dm, SerializerInterface $serializer)
    {
        $this->dm = $dm;
        $this->userRepo = $dm->getRepository(User::class);
        $this->roleRepo = $dm->getRepository(Role::class);
        $this->serializer = $serializer;
    }

    /**
     * @Route("/account", name="user")
     */
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    public function new(Request $request)
    {
        $username = '';
        $password = '';

        $data = $request->getContent();

        if (!empty($data)) {
            $decodedData = \json_decode($data, true);

            $username = $decodedData['username'];
            $password = $decodedData['password'];
        }

        $checkUser = $this->userRepo->findOneBy(['username' => $username]);

        if (!is_null($checkUser)) return new JsonResponse('User already exists', 409);

        $user = new User();
        $user->setUsername($username);
        $user->setPlainPassword($password);

        $role = $this->roleRepo->findOneBy(['code' => 'ROLE_ADMIN']);

        if (is_null($role)) {
            $role = new Role();
            $role->setName('admin');
            $role->setCode('ROLE_ADMIN');

            $this->dm->persist($role);
            $user->setRole($role);
            $role->addUser($user);
        } else {
            $role = $this->roleRepo->findOneBy(['code' => 'ROLE_USER']);

            if (is_null($role)) {
                $role = new Role();
                $role->setName('user');
                $role->setCode('ROLE_USER');

                $this->dm->persist($role);
                $user->setRole($role);
                $role->addUser($user);
            } else {
                $user->setRole($role);
                $role->addUser($user);
            }
        }

        try {
            $this->dm->persist($user);
            $this->dm->flush();

            return new JsonResponse($user, 200);
        } catch (Exception $e) {
            return new JsonResponse(\json_encode($e), 403);
        }
    }
}
