<?php

namespace App\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use \Twilio\Rest\Client;
use App\Entity\Reminder;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Doctrine\ORM\EntityManagerInterface;


#[AsCommand(
    name: 'app:reminders',
    description: 'Send scheduled reminders.',
    hidden: false
)]
class ReminderCommand extends Command {

    protected $em;

    function __construct(EntityManagerInterface $em) {
        $this->em = $em;
        parent::__construct();
    }

 protected function execute(InputInterface $input, OutputInterface $output) {
   $remindersRepository = $this->em->getRepository(Reminder::class);
  
   // For our app, we'll be sending reminders to everyone who has an appointment on this current day, shortly after midnight.
   // As such, the start and end times we'll be checking for will be 12:00am (00:00h) and 11:59pm (23:59h).
   $start = date("Y-m-d");

   // get appointments scheduled for today
   $reminders = $remindersRepository->createQueryBuilder('a')
                                         ->select('a')
                                         ->where('a.reminderDate = :now')
                                         ->andWhere('a.sent = false')
                                         ->andWhere('a.sms = true')
                                         ->setParameters(array(
                                           'now' => $start,
                                         ))
                                         ->getQuery()
                                         ->getResult();
  
   if (count($reminders) > 0) {
     $output->writeln('Reminders to send: #' . count($reminders));

     foreach ($reminders as $r) {
       if($r->getReminderPhone() != null && trim($r->getReminderPhone()) != ""){
            $sid = 'AC9b4cd129def75844087ba232999f27a5';
            $token = '060f2aa17375b7da5570986aa14d5469';
            $fromPhone = '+12133195357';
            try{
                $client = new Client($sid, $token);

                $client->messages->create(
                    $r->getReminderPhone(),
                    [
                        'from' => $fromPhone,
                        'body' => 'First Test'
                    ]
                );
            }catch(\Exception $e){
                $output->writeln('Error sending SMS: '.$e->getMessage());
            }
            
        }
        $r->setSent(true);
        $this->em->flush();
    }
    
   } else {
     $output->writeln('No appointments for today.');
   }

   return Command::SUCCESS;
 }
}
