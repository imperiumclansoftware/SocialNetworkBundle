<?php

    namespace ICS\SocialnetworkBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use ICS\SocialnetworkBundle\Entity\Instagram\InstagramAccount;
use ICS\SocialnetworkBundle\Service\InstagramService;
use Symfony\Component\Routing\Annotation\Route;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

    class InstagramController extends AbstractController
    {
        /**
         * @Route("/instagram", name="ics-socialnetwork-instagram-homepage")
         */
        public function index()
        {
            return $this->render("@Socialnetwork\instagram\instagram.html.twig",[

            ]);
        }

        /**
         * @Route("/instagram/account/{name}", name="ics-socialnetwork-instagram-account")
         */
        public function account(EntityManagerInterface $em, InstagramService $instagramService, $name=null)
        {
            $result=null;

            if($name!=null)
            {
                $result = $em->getRepository(InstagramAccount::class)->findOneBy(['name' => $name]);
                if($result == null)
                {
                    $result = $instagramService->getAccount($name);
                    $em->persist($result);
                    $em->flush();

                }
            }
            
            return $this->render("@Socialnetwork\instagram\account.html.twig",[
                'result' => $result
            ]);
        }

        /**
         * @Route("/instagram/preview", name="ics-socialnetwork-instagram-preview")
         */
        public function accountPreview(Request $request,EntityManagerInterface $em, InstagramService $instagramService)
        {
            $result=null;
            $name=$request->get('account',null);
            
            if($name!=null)
            {
                $account = $em->getRepository(InstagramAccount::class)->findOneBy(['name' => $name]);
                return $this->render('@Socialnetwork/instagram/preview.html.twig',[
                    'account' => $account
                ]);
            }

            return new JsonResponse($name);
        }
    }