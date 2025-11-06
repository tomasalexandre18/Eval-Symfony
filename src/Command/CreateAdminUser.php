<?php

namespace App\Command;

use App\Entity\User;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Exception\ORMException;
use Doctrine\ORM\OptimisticLockException;
use Symfony\Component\Console\Attribute\Argument;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

// the name of the command is what users type after "php bin/console"
#[AsCommand(name: 'app:create-admin')]
class CreateAdminUser
{

    private EntityManagerInterface $entityManager;
    private UserPasswordHasherInterface $passwordHasher;

    public function __construct(EntityManagerInterface $em, UserPasswordHasherInterface $passwordHasherInterface){
        $this->entityManager = $em;
        $this->passwordHasher = $passwordHasherInterface;
    }

    /**
     * @throws OptimisticLockException
     * @throws ORMException
     */
    public function __invoke(
        #[Argument("email of the user")] string $email,
        #[Argument("password of the user")] string $password,
        OutputInterface $output,
    ): int
    {
        // ... put here the code to create the user

        // this method must return an integer number with the "exit status code"
        // of the command. You can also use these constants to make code more readable

        $user = new User();
        $user->setNom("admin");
        $user->setPrenom("admin");
        $user->setRoles(["ADMIN_ROLE"]);
        $user->setEmail($email);
        $user->setPassword($this->passwordHasher->hashPassword($user, $password));
        $user->setRue("admin");
        $user->setCodePostal("00000");
        $user->setVille("admin");

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        // return this if there was no problem running the command
        // (it's equivalent to returning int(0))
        return Command::SUCCESS;

        // or return this if some error happened during the execution
        // (it's equivalent to returning int(1))
        // return Command::FAILURE;

        // or return this to indicate incorrect command usage; e.g. invalid options
        // or missing arguments (it's equivalent to returning int(2))
        // return Command::INVALID
    }
}
