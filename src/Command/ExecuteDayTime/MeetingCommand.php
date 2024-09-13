<?php

namespace App\Command\ExecuteDayTime;

use App\Entity\Calendar\Meeting;
use App\Util\Enum\MeetingStatus;
use Psr\Log\LoggerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class MeetingCommand extends Command
{

    protected static $defaultName = 'app:meeting-pending';

    private $em;
    private $logger;
    private $io;

    public function __construct(
        EntityManagerInterface $em,
        LoggerInterface $logger
    )
    {
        parent::__construct();
        $this->em = $em;
        $this->logger = $logger;
    }

    protected function configure()
    {
        $this
            ->setDescription('Fill category data in the database.')
            ->setHelp('This command category the database with data.');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->io = new SymfonyStyle($input, $output);
        $this->addLogSuccess("MeetingCommand: Start");
        
        $now = new \DateTime();
        $now->setTime(0,0,0);

        $qb = $this->em->getRepository(Meeting::class)->createQueryBuilder('m');
        $qb
            ->andWhere($qb->expr()->eq('m.status',':status'))
            ->andWhere($qb->expr()->lt('m.dateAt',':dateAt'))
            ->setParameter('status',MeetingStatus::PENDING)
            ->setParameter('dateAt',$now)
        ;

        foreach ($qb->getQuery()->getResult() as $meet) {
            $meet->setStatus(MeetingStatus::EXPIRED);
            $this->em->persist($meet);
        }

        $this->em->flush();

        $this->addLogSuccess("MeetingCommand: END");
        return Command::SUCCESS;
    }

    private function addLogSuccess($text)
    {
        $this->io->success($text);
        $this->io->newLine();
        $this->logger->info($text);
    }

    private function addLogInfo($text)
    {
        $this->io->info($text);
        $this->io->newLine();
        $this->logger->info($text);
    }

    private function addLogError($text)
    {
        $this->io->error($text);
        $this->io->newLine();
        $this->logger->info($text);
    }
    
}
