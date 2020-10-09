<?php

namespace App\Controller;

use App\Entity\Commune;
use App\Form\CreateCommuneFormType;
use App\Repository\CommuneRepository;
use App\Repository\MediaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Exception\AlreadySubmittedException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CommuneController extends AbstractController
{
    /**
     * @Route("/api/create/commune", name="commune")
     * @param Request $request
     * @param ValidatorInterface $validator
     * @return JsonResponse
     * @throws AlreadySubmittedException
     */
    public function createCommune(Request $request, ValidatorInterface $validator)
    {
        $commune = new Commune();
        $datas = json_decode($request->getContent(), true);
        $formCreate = $this->createForm(CreateCommuneFormType::class, $commune);

        $formCreate->submit($datas);
        $violation = $validator->validate($commune, null, 'Register');

        if (0 !== count($violation)) {
            foreach ($violation as $error) {
                return new JsonResponse($error->getMessage(), Response::HTTP_BAD_REQUEST);
            }
        }

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($commune);
        $entityManager->flush();
        return new JsonResponse('Commune Created', Response::HTTP_OK);
    }

    /**
     * @Route("/api/commune/delete", name="communeDelete", methods={"DELETE"})
     * @param Request $request
     * @param CommuneRepository $communeRepository
     * @return Response
     */
    public function communeDelete(Request $request, CommuneRepository $communeRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $item = json_decode($request->getContent(),true);
        $commune = $communeRepository->find($item['id']);
        $em->remove($commune);
        $em->flush();
        return new Response("ok");
    }

    /**
     * @Route("/api/commune/update", name="communeUpdate", methods={"PATCH"})
     * @param Request $request
     * @param CommuneRepository $communeRepository
     * @param MediaRepository $mediaRepository
     * @return JsonResponse
     */
    public function communeUpdate(Request $request, CommuneRepository $communeRepository, MediaRepository $mediaRepository)
    {
        $em = $this->getDoctrine()->getManager();
        $item = json_decode($request->getContent(), true);
        $commune = $communeRepository->findOneBy(['id' => $item['id']]);

        isset($item["nom"]) && $commune->setNom($item['nom']);
        isset($item["code"]) && $commune->setCode($item['code']);
        isset($item["codeDepartement"]) && $commune->setcodeDepartement($item['codeDepartement']);
        isset($item["codeRegion"]) && $commune->setcodeRegion($item['codeRegion']);
        isset($item["population"]) && $commune->setpopulation($item['population']);
        isset($item["codesPostaux"]) && $commune->setcodesPostaux($item['codesPostaux']);
        if ($item["media"]) {
            for ($i = 0; $i < count($item["media"]); $i++) {
                $data = $item["media"][$i];
                $media = $mediaRepository->findOneBy(['id' => $data['id']]);
                $media->setImage($data['image'])
                    ->setVideo($data['video'])
                    ->setArticle($data['article']);
                $em->persist($media);
            }
        }
        $em->persist($commune);
        $em->flush();
        return JsonResponse::fromJsonString($this->serializeJson($commune));
    }
}
