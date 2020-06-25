<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Form\BrandType;
use App\Entity\Announcement;
use App\Entity\UserFav;
use App\Form\InfluencerType;
use App\Repository\AnnouncementFavRepository;
use App\Repository\AnnouncementRepository;
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
    public function list($role): Response
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

        return $this->render('user/'.$role.'/list.html.twig', [
            "users" => $users,
        ]);
    }


    /**
     * @Route("/profil/{id}/modifier", name="user_edit", requirements={"role": "^(marque|influenceur)", "id": "\d+"}, methods={"GET","POST"})
     */
    public function edit(User $user,  Request $request, UserPasswordEncoderInterface $passwordEncoder, ImageUploader $imageUploader): Response
    {
        $this->denyAccessUnlessGranted('edit', $user);
        
        if ( in_array( "ROLE_INFLUENCER", $user->getRoles() ) ){
            $form = $this->createForm(InfluencerType::class, $user);
        }
        elseif ( in_array( "ROLE_BRAND", $user->getRoles() ) ){
            $form = $this->createForm(BrandType::class, $user);
        }
        else{
            $form = $this->createForm(UserType::class, $user);
        }

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {

            $imageName = $imageUploader->getRandomFileName('jpg');
            if($imageUploader->moveFile($form->get('picture')->getData(), "avatar_user")){
                $user->setPicture($imageName);
                
            };
            $password = $form->get('password')->getData();
            if ($password != null)
            {
                $encodedPassword = $passwordEncoder->encodePassword($user, $password);
                $user->setPassword($encodedPassword);
            }

            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_show');
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
            $influencer = $user;

            return $this->render('user/influencer/show.html.twig', [
                'influencer' => $influencer,
            ]);
        }
        
        if (in_array("ROLE_BRAND", $user->getRoles()))
        {
            $brand = $user;
            
            return $this->render('user/brand/show.html.twig', [
                'brand' => $brand,
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
}
