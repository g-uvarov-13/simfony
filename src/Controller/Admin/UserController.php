<?php

namespace App\Controller\Admin;

use App\Entity\StaticStorage\UserStaticStorage;
use App\Form\Admin\EditUserFormType;
use App\Entity\Category;
use App\Entity\Product;
use App\Entity\User;
use App\Form\Admin\EditCategoryFormType;
use App\Form\Handler\CategoryFormHandler;
use App\Form\Handler\ProductFormHandler;
use App\Form\Handler\UserFormHandler;
use App\Form\Model\EditCategoryModel;
use App\Repository\CategoryRepository;
use App\Repository\UserRepository;
use App\Utils\Manager\CategoryManager;
use App\Utils\Manager\UserManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/admin/user", name="admin_user_")
 */
class UserController extends AbstractController
{

    /**
     * @Route("/list", name="list")
     * @param UserRepository $userRepository
     * @return Response
     */
    public function list(UserRepository $userRepository): Response
    {
        if(!$this->isGranted(UserStaticStorage::USER_ROLE_SUPER_ADMIN)){
            return $this->redirectToRoute('admin_dashboard_show');
    }
        $users = $userRepository->findBy(['isDeleted' => false ],['id' => "DESC"]);

        return $this->render('admin/user/list.html.twig', [
            'users' => $users,
        ]);
    }

    /**
     * @Route("/edit/{id}", name="edit")
     * @Route("/add", name="add")
     * @param Request $request
     * @param UserFormHandler $userFormHandler
     * @param User|null $user
     * @return Response
     */
    public function edit(Request $request,UserFormHandler $userFormHandler, User $user = null): Response
    {
        if(!$this->isGranted(UserStaticStorage::USER_ROLE_SUPER_ADMIN)){
            return $this->redirectToRoute('admin_dashboard_show');
        }

        if(!$user){
            $user = new User();
        }

        $form = $this->createForm(EditUserFormType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            //
            $user = $userFormHandler->proccesEditForm($form);
            $this->addFlash('success','Ваши изменения были сохранены!');
            return $this->redirectToRoute('admin_user_edit', ['id' => $user->getId()]);
        }
        if($form->isSubmitted() && !$form->isValid()){
            $this->addFlash('warning','Неправильно введённые данные');
        }
        return $this->render('admin/user/edit.html.twig',[
            'user' => $user,
            'form' =>$form->createView()
        ]);
    }

    /**
     * @Route("/delete/{id}", name="delete")
     * @param User $user
     * @param UserManager $userManager
     * @return Response
     */
    public function delete(User $user, UserManager $userManager): Response
    {
        if(!$this->isGranted(UserStaticStorage::USER_ROLE_SUPER_ADMIN)){
            return $this->redirectToRoute('admin_dashboard_show');
        }
        $userManager->remove($user);
        $this->addFlash('warning','Пользователь был успешно удалён');
        return $this->redirectToRoute('admin_user_list');
    }
}

