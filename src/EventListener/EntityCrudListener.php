<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\DependencyInjection\Container;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Symfony\Bundle\SecurityBundle\Security;
use App\Entity\Reminder;
use Twilio\Rest\Client;
use Symfony\Component\Dotenv\Dotenv;

class EntityCrudListener {
    
    protected $container;
    private $security;

    public function __construct(Container $container, Security $security) {
        $this->container= $container;
        $this->security = $security;
        
    }

    public function prePersist(PrePersistEventArgs $args) {
        $entity = $args->getObject();
        $entityManager = $args->getObjectManager();
        if($entity instanceof Reminder){
            $today = new \DateTime(date("Y-m-d"));
            if($entity->getReminderDate() == $today){
                if($entity->isSms() && $entity->getReminderPhone() != null && trim($entity->getReminderPhone()) != ""){
                    $sid = 'AC9b4cd129def75844087ba232999f27a5';
                    $token = '060f2aa17375b7da5570986aa14d5469';
                    $fromPhone = '+12133195357';
                    try{
                        $client = new Client($sid, $token);
                        $client->messages->create(
                            $entity->getReminderPhone(),
                            [
                                'from' => $fromPhone,
                                'body' => 'First Test'
                            ]
                        );
                        $entity->setSent(true);
                    }catch(\Exception $e){
                        $entity->setSent(false);
                    }
                    
                
            }
        }

    }

    }
}

