<?php

namespace AppBundle\Security;

use AppBundle\Entity\User;

use GuzzleHttp\Client;
use JMS\Serializer\Serializer;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Doctrine\Common\Persistence\ObjectManager;

class FacebookUserProvider implements UserProviderInterface
{
    private $client;
    private $em;

    public function __construct(Client $client, Serializer $serializer, ObjectManager $em)
    {
        $this->client = $client;
        $this->serializer = $serializer;
        $this->em = $em;
    }

    public function loadUserByUsername($username)
    {
        $url = 'https://graph.facebook.com/me?access_token='.$username.'&fields=id,name,email,gender';

        $response = $this->client->get($url);

        $res = $response->getBody()->getContents();
        $userData = $this->serializer->deserialize($res, 'array', 'json');

        if (!$userData) {
            throw new \LogicException('Did not managed to get your user info from Facebook.');
        }

        $user = new User($userData['name'], $userData['id'], $userData['email'], $userData['gender']);
        
        //check if this user exist in bilemo database
        $checkUser = $this->em->getRepository('AppBundle:User')->findOneByEmail($user->getEmail());

        //if it doesn't exist, we save it befor return the user 
        if($checkUser)
        {
            return $user;
        }
        else
        {
            $save = $this->em;
            $save->persist($user);
            $save->flush();

            return $user;
        }
       
        //return new User($userData['name'], $userData['id'], $userData['email'], $userData['gender']);
    }

    public function refreshUser(UserInterface $user)
    {
        $class = get_class($user);
        if (!$this->supportsClass($class)) {
            throw new UnsupportedUserException();
        }

        return $user;
    }

    public function supportsClass($class)
    {
        return 'AppBundle\Entity\User' === $class;
    }
}