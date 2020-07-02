<?php

namespace App\Controller;

use App\Entity\SocialNetwork;
use App\Entity\User;
use App\Form\UserType;
use App\Entity\UserFav;
use App\Entity\UserReport;
use App\Entity\UserSocial;
use App\Form\BrandEditType;
use App\Form\InfluencerEditType;
use App\Form\UserSocialType;
use App\Repository\AnnouncementFavRepository;
use App\Repository\AnnouncementRepository;
use App\Repository\CategoryRepository;
use App\Repository\UserFavRepository;
use App\Service\ImageUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/{role}/liste", name="user_list", methods={"GET"}, requirements={"role": "^(marque|influenceur|utilisateur)"})
     */
    public function list($role, CategoryRepository $categoryRepository): Response
    {
        if($role === "influenceur"){
            $role = "influencer";
            $users = $this->getDoctrine()->getRepository(User::class)->findByRole('["ROLE_INFLUENCER"]');
        }
        elseif($role === "marque"){
            $role = "brand";
            $users = $this->getDoctrine()->getRepository(User::class)->findByRole('["ROLE_BRAND"]');
        }
        elseif($role === "user"){
            $role = "user";
            $users = $this->getDoctrine()->getRepository(User::class)->findAll();
        }
        $categories = $categoryRepository->findAll();
        return $this->render('user/'.$role.'/list.html.twig', [
            "users" => $users,
            'categories' => $categories
        ]);
    }


    /**
     * @Route("/profil/{id}/modifier", name="user_edit", requirements={"role": "^(marque|influenceur)", "id": "\d+"}, methods={"GET","POST"})
     */
    public function edit(User $user,  Request $request, UserPasswordEncoderInterface $passwordEncoder, ImageUploader $imageUploader): Response
    {
        $this->denyAccessUnlessGranted('edit', $user);


        if ( in_array( "ROLE_INFLUENCER", $user->getRoles() ) ){
            $form = $this->createForm(InfluencerEditType::class, $user);
        }
        elseif ( in_array( "ROLE_BRAND", $user->getRoles() ) ){
            $form = $this->createForm(BrandEditType::class, $user);
        }
        else{
            $form = $this->createForm(UserType::class, $user);
        }

      

        $form->handleRequest($request);
  
        if ($form->isSubmitted() && $form->isValid())
        {   
            $imageName = $imageUploader->moveFile($form->get('pictureFile')->getData(), "avatar_user");
            if($imageName){
                $user->setPicture($imageName);
            };
            $password = $form->get('password')->getData();
            if ($password != null)
            {
                $encodedPassword = $passwordEncoder->encodePassword($user, $password);
                $user->setPassword($encodedPassword);
            }
            $user->setUpdatedAt(new \DateTime());

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_show', ['id'=>$user->getId()]);
        }


        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user
        ]);
    }

    /**
     * @Route("/profil/{id}", name="user_show", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function show(User $user): Response
    {

        if (in_array("ROLE_INFLUENCER", $user->getRoles()))
        {
            return $this->render('user/influencer/show.html.twig', [
                'user' => $user,
            ]);
        }
        
        if (in_array("ROLE_BRAND", $user->getRoles()))
        {
            // Calculate overall favorites get from announcements
            $likes = 0;
            foreach ( $user->getAnnouncements() as $announcement) {
                $announcementLikes = count($announcement->getFavorites()) + 1;
                $likes += $announcementLikes;
            };
            return $this->render('user/brand/show.html.twig', [
                'user' => $user,
                'likes' => $likes
            ]);
        }

        else
        {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }

    }

    /**
     * @Route("/dashboard/{id}", name="user_dashboard", methods={"GET"}, requirements={"id": "\d+"})
     */
    public function userDashboard(User $user, AnnouncementFavRepository $announcementFavRepo, AnnouncementRepository $annoucementRepo)
    {
        $this->denyAccessUnlessGranted('dashboard', $user);
        if (in_array("ROLE_INFLUENCER", $user->getRoles())) {
            $influencer = $user;
            $favorites = $announcementFavRepo->findByInfluencerId($influencer->getId());
            return $this->render('user/influencer/dashboard.html.twig', [
                'favorites'=>$favorites,
                'user' => $user
            ]);
        }
        
        if (in_array("ROLE_BRAND", $user->getRoles())) {
            $brand = $user;
            $announcements = $annoucementRepo->findByBrandId($brand->getId());
            return $this->render('user/brand/dashboard.html.twig', ['announcements'=>$announcements, 'user'=>$user]);
        }

        else
        {
            throw $this->createNotFoundException('Utilisateur introuvable');
        }
    }

/**
     * 
     * add or remove an announcement to the favorites
     * 
     * @Route("user/{id}/favoris", name="user_favorite")
     *
     * @param User $userLiked
     * @param ObjectManager $manager
     * @param UserFavRepository $favRepo
     * @return Response
     */
    public function favorites(User $userLiked, EntityManagerInterface $manager, UserFavRepository $favRepo): Response
    {
        $user =$this->getUser();

        if(!$user){

        return $this->json(['code'=>403, 'message'=>'Unauthorizer'], 403);

        }
        if ($userLiked->isFavByUser($user)){
            $favorite = $favRepo->findOneBy([
                'userLiked'=>$userLiked,
                'userLike'=>$user
            ]);
           
            $manager->remove($favorite);
            $manager->flush();

            return $this->json(['code'=>200, 'message'=> 'L\'utilisateur '.  $userLiked->getUsername() . ' a été retirée de vos favoris !'], 200);
        }

        $favorite = new UserFav();
        $favorite->setUserLiked($userLiked);
        $favorite->setUserLike($user);

        $manager->persist($favorite);
        $manager->flush();
        return $this->json(['code'=>200, 'message'=> 'L\'utilisateur '. $userLiked->getUsername() . ' a été ajoutée à vos favoris !'], 200);
    }

        /**
     * @Route("/user/{id}/social/add", name="social_add", requirements ={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function addUserSocial(User $user, Request $request){

        $this->denyAccessUnlessGranted('add_social', $user);

        $userSocial = new UserSocial();
        $userSocial->setUser($user);

        $form = $this->createForm(UserSocialType::class, $userSocial);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($userSocial);
            $manager->flush();
            return $this->redirectToRoute('social_profile', ['id'=>$user->getId()]);
        }

        return $this->render('social/add_social.html.twig', [
            "form" => $form->createView(),
        ]);

    }

     /**
     * @Route("/user/{id}/social/edit", name="social_edit", requirements ={"id" = "\d+"}, methods={"GET", "POST"})
     */
    public function editUserSocial( UserSocial $userSocial,Request $request){

        $this->denyAccessUnlessGranted('edit', $userSocial);


        $form = $this->createForm(UserSocialType::class, $userSocial);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager = $this->getDoctrine()->getManager();
            $manager->persist($userSocial);
            $manager->flush();
            return $this->redirectToRoute('social_profile', ['id'=>$userSocial->getUser()->getId()]);
        }

        return $this->render('social/edit_social.html.twig', [
            "userSocial"=>$userSocial,
            "form" => $form->createView(),
        ]);

    }


    /** 
    * @Route("/user/{id}/social", name="social_profile", requirements ={"id" = "\d+"}, methods={"GET"})
    */
    public function showUserSocial(User $user){

        $this->denyAccessUnlessGranted('show_social', $user);

        return $this->render('social/social_profile.html.twig', [
            "user" => $user
        ]);
        
    }
  
    /**
     * Report an user
     * 
     * @Route("/user/{id}/signaler", name="user_report")
     * 
     * @param User $reportee
     * @param EntityManagerInterface $manager
     * @return Response
     */
    public function report(User $reportee, EntityManagerInterface $manager): Response
    {
        $user = $this->getUser();

        if (!$user) {
            return $this->json(['code' => 403, 'message' => 'Unauthorized'], 403);
        }

        if ($reportee->isReportedByUser($user)) {
            return $this->json(['code' => 200, 'message '=> 'Vous avez déjà signalé cet utilisateur !'], 200);
        }

        $report = new UserReport();
        $report->setReportee($reportee);
        $report->setReporter($user);

        $manager->persist($report);
        $manager->flush();
        return $this->json(['code' => 200, 'message' => 'L\'utilisateur '. $reportee->getUsername() . ' a été signalé par '. $user->getUsername() . '.'], 200);
    }

}