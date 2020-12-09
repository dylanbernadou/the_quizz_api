<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class GetAuthUser
{
	public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @Route (
	 *	name="get_authenticated_user",
	 *	path="/api/get_auth",
	 *	methods={"GET"}
     * )
     */
    public function __invoke(SerializerInterface $serializer): Response
    {
    	$response = new Response();
        $response->headers->set('Content-Type', 'application/json');

    	$user = $this->tokenStorage->getToken()->getUser();

    	if ($user) {
    		$json = $serializer->serialize($user, 'json');

    		$response->setContent($json);
    		return $response->setStatusCode(200);
    	} else {
    		return $response->setStatusCode(403);
    	}
    }
}
