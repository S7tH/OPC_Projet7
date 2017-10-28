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

        //if exist user has the rights
        if($checkUser)
        {
            if($checkUser->getRoles() !== ['ROLE_USER'])
            {
                $user->setRoles(['ROLE_ADMIN']);
            }
            else
            {
                $user->setRoles(['ROLE_USER']);
            }    
        
    
            //if the current datas are differents we update them
            if($checkUser != $user)
            {
                $checkUser->setUsername($user->getUsername());
                $checkUser->setFacebookId($user->getFaceBookId());
                $checkUser->setGender($user->getGender());
                $save = $this->em;
                $save->persist($checkUser);
                $save->flush();
            }
            return $user;
        }
        else
        {
            $user->setRoles(['ROLE_UNREGISTERED']);
            
            return $user;
        }
       
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
