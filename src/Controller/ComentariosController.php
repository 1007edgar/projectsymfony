<?php

namespace App\Controller;

use App\Entity\Comentarios;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ComentariosController extends AbstractController
{
    /**
     * @Route("/comentarios", name="comentarios")
     */
    public function index(Request $request, $id): Response
    {
        
        return $this->render('comentarios/index.html.twig');
    }
}
