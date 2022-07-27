<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ChoiceQuestion;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'admin:add',
    description: 'Add a user to the admin group',
)]
class AdminAddCommand extends Command
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        parent::__construct();

        $this->em = $em;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $users = $this->em->getRepository(User::class)->findAll();

        if ([] === $users) {
            $io->error('No users found');

            return Command::FAILURE;
        }

        /** @var QuestionHelper */
        $helper = $this->getHelper('question');

        $question = new ChoiceQuestion('Which user should be added to the admin group?', $users, 0);

        /** @var User */
        $user = $helper->ask($input, $output, $question);

        $user->setRoles(['ROLE_ADMIN']);

        $this->em->persist($user);
        $this->em->flush();

        $io->success(sprintf('User "%s" now is an admin', (string) $user));

        return Command::SUCCESS;
    }
}
