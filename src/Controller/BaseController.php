<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class BaseController extends AbstractController
{
    private $em;
    private $validator;

    public function __construct(ManagerRegistry $em, ValidatorInterface $validator)
    {
        $this->em = $em->getManager();
        $this->validator = $validator;
    }

    public function getManager(){
        return $this->em;
    }

    public function getValidator(){
        return $this->validator;
    }
    
}
