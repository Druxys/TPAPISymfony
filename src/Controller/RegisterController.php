<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegisterFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class RegisterController extends AbstractController
{
    /**
     * @Route("/register", name="register", methods={"POST"})
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     */
    public function registerUser(Request $request, ValidatorInterface $validator)
    {
        $user = new User();
        $datas = json_decode($request->getContent(), true);
        $formRegister = $this->createForm(RegisterFormType::class, $user);

        $formRegister->submit($datas);
        $violation = $validator->validate($user, null, 'Register');

        if (0 !== count($violation)) {
            foreach ($violation as $error) {
                return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return new JsonResponse('User Created', Response::HTTP_OK);
    }
}
