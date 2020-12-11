<?php

namespace App\DataPersister;

use App\Entity\Instance;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Validator\Constraints\DateTime;
use ApiPlatform\Core\DataPersister\ContextAwareDataPersisterInterface;

/**
 *
 */
class InstanceDataPersister implements ContextAwareDataPersisterInterface
{
    private $_entityManager;

    /**
     * @param Request
     */
    private $_request;

    /**
     * @param Security
     */
    private $_security;


    public function __construct(
        EntityManagerInterface $entityManager,
        RequestStack $request,
        Security $security
    ) {
        $this->_entityManager = $entityManager;
        $this->_request = $request->getCurrentRequest();
        $this->_security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function supports($data, array $context = []): bool
    {
        return $data instanceof Instance;
    }

    /**
     * @param Instance $data
     */
    public function persist($data, array $context = [])
    {
        if ($this->_request->getMethod() === 'POST') {
            $data->setCreationDateTime(
                new \DateTime('now', new \DateTimeZone('Europe/Paris'))
            );

            $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
            $charactersLength = strlen($characters);
            $randomString = '';
            for ($i = 0; $i < 6; $i++) {
                $randomString .= $characters[rand(0, $charactersLength - 1)];
            }

            $data->setCode($randomString);

            $data->setCreator($this->_security->getUser());
        }
        

        $this->_entityManager->persist($data);
        $this->_entityManager->flush();
    }

    /**
     * {@inheritdoc}
     */
    public function remove($data, array $context = [])
    {
        $this->_entityManager->remove($data);
        $this->_entityManager->flush();
    }
}