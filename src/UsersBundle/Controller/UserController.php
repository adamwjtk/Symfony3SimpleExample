<?php

namespace UsersBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use UsersBundle\Service\Faker\FakeUsersListGenerate;

class UserController extends Controller implements UserInterface
{

    /**
     * @Route("/api/v1/user/random/list")
     * @param int $amount
     * @return JsonResponse
     */
    public function RandomUserListAction(int $amount = self::FAKE_USERS_QUANTITY): JsonResponse
    {
        $fakeUserDataCreateService = $this->get('user.fake.generate_random_list');
        $data = $fakeUserDataCreateService->generateList($amount);

        if (is_array($data) && false === empty($data)) {
            return JsonResponse::create($data, JsonResponse::HTTP_OK, []);
        }

        return JsonResponse::create([], JsonResponse::HTTP_NOT_FOUND, []);
    }

    /**
     * @Route("/api/v1/user/random/list2")
     * @param int $amount
     * @return JsonResponse
     */
    public function RandomUserListSecondAction(int $amount = self::FAKE_USERS_QUANTITY, FakeUsersListGenerate $fakeUsersListGenerate): JsonResponse
    {

        $data = $fakeUsersListGenerate->generateList($amount);

        if (is_array($data) && false === empty($data)) {
            return JsonResponse::create($data, JsonResponse::HTTP_OK, []);
        }

        return JsonResponse::create([], JsonResponse::HTTP_NOT_FOUND, []);
    }

    /**
     * @Route("/user/table")
     */
    public function displayDataTableAction()
    {
        $data = $this->forward('UsersBundle:User:RandomUserList')->getContent();

        $return =  $this->renderView('@Users/Tables/UserDataTable.html.twig', array('data' => json_decode($data, true),'title' => 'task 3'));

        return new Response($return);
    }
}
