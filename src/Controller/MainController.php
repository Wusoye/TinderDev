<?php

namespace App\Controller;

use App\Repository\LangageRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class MainController extends AbstractController
{
    #[Route('/', name: 'main_acceuil')]
    public function index(): Response
    {
        return $this->render('main/index.html.twig', [
            'controller_name' => 'MainController',
        ]);
    }

    #[Route('/dev/index', name: 'dev_acceuil')]
    public function acceuil(LangageRepository $langageRepository, UserRepository $userRepository): Response
    {

        $langageList = $langageRepository->findAll();
        $userList = $userRepository->findAll();

        $amiList = [];

        foreach ($userList[3]->getAmis() as $unAmi) {
            $amiList[] = $unAmi;
        }

        dump($amiList);

        return $this->render('main/acceuil.html.twig', [
            'controller_name' => 'MainController',
            'langageList' => $langageList,
            'userList' => $userList
        ]);
    }

    #[Route('/dev/langage_filter/{id}', name: 'langage_filter')]
    public function filter($id, LangageRepository $langageRepository, UserRepository $userRepository): Response
    {

        $langageList = $langageRepository->findAll();
        $userList = $userRepository->findAll();
        $userListFilter = [];

        foreach ($userList as $user) {
            foreach ($user->getLangages() as $unLangage) {
                if ($unLangage->getId() == $id) {
                    $userListFilter[] = $user;
                }
            }
        }

        return $this->render('main/acceuil.html.twig', [
            'controller_name' => 'MainController',
            'langageList' => $langageList,
            'userList' => $userListFilter
        ]);
    }

    #[Route('/api/add_friend/{idOwner}/{idFriend}', name: 'api_addFriend')]
    public function addFriend($idOwner, $idFriend, EntityManagerInterface $entityManager, LangageRepository $langageRepository, UserRepository $userRepository): JsonResponse
    {

        $user = $userRepository->find($idOwner);
        $ami = $userRepository->find($idFriend);
        $estAmi = false;

        $amiList = [];

        foreach ($user->getAmis() as $unAmi) {
            $amiList[] = $unAmi;
        }

        if (in_array($ami, $amiList)) {
            $user->removeAmi($ami);
        } else {
            $user->addAmi($ami);
            $estAmi = true;
        }

        $entityManager->persist($ami);
        $entityManager->flush();

        $response = new JsonResponse(json_encode(array("estAmi" => $estAmi)));
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    #[Route('/api/remove_friend/{idOwner}/{idFriend}', name: 'api_removeFriend')]
    public function removeFriend($idOwner, $idFriend, EntityManagerInterface $entityManager, LangageRepository $langageRepository, UserRepository $userRepository): JsonResponse
    {

        $user = $userRepository->find($idOwner);
        $ami = $userRepository->find($idFriend);

        $user->removeAmi($ami);

        $entityManager->persist($ami);
        $entityManager->flush();

        $response = new JsonResponse(json_encode(array()));
        $response->headers->set('Access-Control-Allow-Origin', '*');
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
