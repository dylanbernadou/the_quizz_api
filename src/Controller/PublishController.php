<?php
// src/Controller/PublishController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\Publisher;
use Symfony\Component\Mercure\Update;

class PublishController
{
    public function __invoke(Publisher $publisher): Response
    {
        $update = new Update(
            'http://localhost:8000/users/5',
            "Salut mon bro !"
        );

        // The Publisher service is an invokable object
        $publisher($update);

        return new Response('published!');
    }
}