<?php

namespace App\Command;

use App\Entity\User;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use \Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Stopwatch\Stopwatch;


class AddUserCommand extends Command
{
    protected static $defaultName = 'app:add-user';
    protected static $defaultDescription = 'Create user';
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;
    /**
     * @var UserPasswordHasherInterface
     */
    private $encoder;
    /**
     * @var UserRepository
     */
    private $userRepository;

    /**
     * AddUserCommand constructor.
     * @param string|null $name
     * @param EntityManagerInterface $entityManager
     * @param UserPasswordHasherInterface $encoder
     * @param UserRepository $userRepository
     */
    public function __construct(string $name = null, EntityManagerInterface $entityManager, UserPasswordHasherInterface $encoder, UserRepository $userRepository)
    {
        parent::__construct($name);
        $this->entityManager = $entityManager;
        $this->encoder = $encoder;
        $this->userRepository = $userRepository;
    }

    protected function configure(): void
    {
        $this
            ->addOption('email','email', InputArgument::REQUIRED, 'Email')
            ->addOption('password','pass', InputArgument::REQUIRED, 'Пароль')
            ->addOption('isAdmin', null, InputArgument::OPTIONAL, 'Если отмечено, пользователь создастся как администратор',0)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $stopWatch =new StopWatch();
        $stopWatch->start('add-user-command');
        $email = $input->getOption('email');
        $password = $input->getOption('password');
        $isAdmin = $input->getOption('isAdmin');

        $io->title('Add User Command Wizard');
        $io->text([
            'Please, enter some information'
        ]);

        if(!$email){
            $email = $io->ask('Введите email');
        }
        if(!$password){
            $password = $io->askHidden('Введите пароль(Вводимое, не будет показываться)');
        }
        if(!$isAdmin){
            $question = new Question('У пользователя права админа?(1 или 0)',0);
            $isAdmin = $io->askQuestion($question);
        }

        $isAdmin = boolval($isAdmin);
        try{
            $user = $this->createUser($email,$password,$isAdmin);
        } catch(RuntimeException $exception){
            $io->comment($exception->getMessage());
            return Command::FAILURE;
        }




        $successMessage = sprintf('%s был успешно создан: %s',
            $isAdmin ? 'Admin User' : 'User',
            $email);
        $io->success($successMessage);
        $event = $stopWatch->stop('add-user-command');
        $stopWatchMessage = sprintf('New user\' id: %s / Elapsed time: %.2f ms / Consumed Memory: %.2f MB',
        $user->getId(),
        $event->getDuration(),
            $event->getMemory() / 1000 / 1000
        );
        $io->comment($stopWatchMessage);

        return Command::SUCCESS;
    }

    /**
     * @param string $email
     * @param string $password
     * @param bool $isAdmin
     * @return User
     */
    private function createUser(string $email,string $password, bool $isAdmin): User
    {


        $existingUser = $this->userRepository->findOneBy(['email' => $email]);
        if($existingUser){
            throw new RuntimeException('Пользователь с таким email уже существует');
        }

        $user = new User;
        $user->setEmail($email);
        $user->setRoles([$isAdmin ? 'ROLE_ADMIN' : 'ROLE_USER']);

        $encodedPassword = $this->encoder->hashPassword($user,$password);
        $user->setPassword($encodedPassword);

        $user->setIsVerified(true);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }
}
