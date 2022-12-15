<?php


namespace App\Form\Handler;



use App\Entity\User;
use App\Form\Model\EditCategoryModel;
use App\Utils\Manager\CategoryManager;
use App\Utils\Manager\UserManager;
use Symfony\Component\Form\Form;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserFormHandler
{

    /**
     * @var UserManager
     */
    private $userManager;

    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * UserFormHandler constructor.
     * @param UserManager $userManager
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserManager $userManager, UserPasswordHasherInterface  $passwordHasher )
    {
        $this->userManager = $userManager;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * @param Form $form
     * @return mixed
     */
    public function proccesEditForm(Form $form)
    {
        $plainPassword = $form->get('plainPassword')->getData();
        $newEmail = $form->get('newEmail')->getData();

        $user = $form->getData();

        if(!$user->getId()){
            $user->setEmail($newEmail);
        }

        if ($plainPassword) {
            $encodedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($encodedPassword);
        }

        $this->userManager->save($user);

        return $user;
    }
}