<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class SetAdminRoleCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    protected function configure()
    {
        $this->setName('app:set-admin-role')
            ->setDescription('Set ROLE_ADMIN to an existing user.')
            ->addArgument('email', InputArgument::REQUIRED, 'Email of the user');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $email = $input->getArgument('email');
        $user = $this->entityManager->getRepository(User::class)->findOneBy(['email' => $email]);

        if ($user) {
            // Ajoute le rôle ROLE_ADMIN
            $roles = $user->getRoles();
            if (!in_array('ROLE_ADMIN', $roles)) {
                $roles[] = 'ROLE_ADMIN';
                $user->setRoles($roles);
                $this->entityManager->flush();
                $output->writeln("Le rôle ROLE_ADMIN a été attribué à l'utilisateur avec l'email : $email");
            } else {
                $output->writeln("L'utilisateur a déjà le rôle ROLE_ADMIN.");
            }
        } else {
            $output->writeln("Utilisateur avec l'email $email non trouvé.");
        }

        return Command::SUCCESS;
    }
}