<?php

namespace App\Controller;

use App\Entity\Posts;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractController
{
    /**
     * @Route("/", name="dashboard")
     */
    public function index(PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        if ($user) {
            $em = $this->getDoctrine()->getManager();//traer todos los posts de la base de datos
            //$posts = $em->getRepository(Posts::class)->findAll();//find(id);
            $query = $em->getRepository(Posts::class)->BuscarTodosLosPosts();
            //$post = $em->getRepository(Posts::class)->findOneBy(['titulo' => 'KTM 790 Duke']);
            //$post = $em->getRepository(Posts::class)->findBy(['likes'=>'']);
            $pagination = $paginator->paginate(
                $query, /* query NOT result */
                $request->query->getInt('page', 1), /*page number*/
                2 /*limit per page*/
            );
        
            return $this->render('dashboard/index.html.twig', [
                'controller_name' => 'Posts Creados por Usuarios',
                //'posts' => $posts,
                'pagination' => $pagination,
            ]);
        }
        else {
            return $this->redirectToRoute('app_login');
        }
        
    }
}
