<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\InstanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class JoinInstance
{
	public function __construct(TokenStorageInterface $tokenStorage, InstanceRepository $instanceRepo, EntityManagerInterface $entityManager)
    {
        $this->tokenStorage = $tokenStorage;
        $this->instanceRepo = $instanceRepo;
        $this->entityManager = $entityManager;
    }

    /**
     * @Route (
	 *	name="join_instance",
	 *	path="/api/join_instance",
	 *	methods={"POST"}
     * )
     */
    public function __invoke(SerializerInterface $serializer, Request $request, PublisherInterface $publisher): Response
    {
    	$response = new Response();
        $response->headers->set('Content-Type', 'application/json');

    	$user = $this->tokenStorage->getToken()->getUser();

    	if ($user) {
            $instance = $this->instanceRepo->find(json_decode($request->getContent(), true)['id']);
            $instance->addPlayer($user);

            $this->entityManager->persist($instance);
            $this->entityManager->flush();

            $json = $serializer->serialize($instance, 'json');

            $update = new Update(
                'http://localhost:8000/instances/' . $instance->getId(),
                $json
            );
            
            $publisher($update);
            
            $response->setContent($json);
    		return $response->setStatusCode(200);
    	} else {
    		return $response->setStatusCode(403);
    	}
    }
}
