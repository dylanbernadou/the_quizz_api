<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\InstanceRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mercure\PublisherInterface;
use Symfony\Component\Mercure\Update;

class DeleteInstance
{
	/**
     * @Route (
	 *	name="delete_instance",
	 *	path="/api/delete_instance/{id}",
	 *	methods={"DELETE"}
     * )
     */
    public function __invoke(int $id, InstanceRepository $instanceRepo, EntityManagerInterface $em, PublisherInterface $publisher): Response
    {
    	$response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $instance = $instanceRepo->find($id);
        $players = $instance->getPlayers();

        foreach ($players as $player) {
        	$instance->removePlayer($player);
        }

        $update = new Update(
            'http://localhost:8000/instances/' . $instance->getId(),
            "Deleted"
        );
        
        $publisher($update);

        $em->remove($instance);
        $em->flush();

        return $response->setStatusCode(200);
    }

}