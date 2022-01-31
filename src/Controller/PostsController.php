<?php

namespace App\Controller;

use App\Entity\Comentarios;
use App\Entity\Posts;
use App\Form\ComentariosType;
use App\Form\PostsType;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostsController extends AbstractController
{
    /**
     * @Route("/registrar-posts", name="RegistrarPosts")
     */
    public function index(Request $request, SluggerInterface $slugger): Response
    {   
        $post = new Posts();
        $form = $this->createForm(PostsType::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {//
            $brochureFile = $form->get('foto')->getData();//datos que vienen desde el formulario
            if ($brochureFile) {
                $originalFilename = pathinfo($brochureFile->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$brochureFile->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $brochureFile->move(
                        $this->getParameter('photos_directory'),//lo mueve a parameters : config/services.yaml
                        $newFilename
                    );
                } catch (FileException $e) {
                    throw new \Exception('Ups! ha ocurrido un error!');//si pasa algo , mostrará el error
                    // ... handle exception if something happens during file upload
                }

                // updates the 'brochureFilename' property to store the PDF file name
                // instead of its contents
                $post->setFoto($newFilename);//entidad post y nuestro metodo es setFoto
            }
            $user = $this->getUser();
            $post->setUser($user);
            $em = $this->getDoctrine()->getManager();//para guardar en la base de datos
            $em->persist($post);
            $em->flush();
            return $this->redirectToRoute('dashboard');
        }
        return $this->render('posts/index.html.twig', [
            'controller_name' => 'Registrar Nuevo Post',
            'form' => $form->createView(),
        ]);
    }

     /**
     * @Route("/post/{id}", name="verPost")
     */
    public function verPost($id, Request $request){
        $em = $this->getDoctrine()->getManager();//emtity manager
        $post = $em->getRepository(Posts::class)->find($id);
        $comentarios = new Comentarios();
        $form = $this->createForm(ComentariosType::class, $comentarios);
        $form->handleRequest($request);
        //Añadiendo comentarios
        if ($form->isSubmitted() && $form->isValid()) { 
            $user = $this->getUser();
            $comentarios->setUser($user);//añade el usuario logueado a la tabla comentarios
            $comentarios->setPosts($post);//añade el id del post a la tabla comentarios
            $em = $this->getDoctrine()->getManager();
            $em->persist($comentarios);
            $em->flush();
            /**
             * redirige a la misma página 
             * porque la documentación de Symfony ( handling-form-submissions ) también..
             *  muestra un ejemplo que redirige a otro controlador.
             */
            return $this->redirect($request->getUri());//redirigir
        }
        $miscomentarios = $em->getRepository(Comentarios::class)->findOneBy(['posts'=>$id]);
        return $this->render('posts/verPost.html.twig', [
            'post' => $post,
            'comentarios' => $form->createView(),
            'mis_comentarios' => $miscomentarios,
        ]);
        
    }

    /**
     * @Route("/mis-posts", name="misPosts")
     */
    public function misPosts(){
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $posts = $em->getRepository(Posts::class)->findBy(['user'=>$user]);
        return $this->render('posts/misPosts.html.twig', [
            'posts' => $posts,
        ]);
    }

    /**
     * @Route("/Likes", options={"expose"=true}, name="Likes")
     */
    public function Like(Request $request)
    {
        if ($request->isXmlHttpRequest()) {//si es ajax , entonces ...
            $em = $this->getDoctrine()->getManager();
            $user = $this->getUser();
            $id = $request->request->get('id');
            $post = $em->getRepository(Posts::class)->find($id);
            $likes = $post->getLikes();
            $likes .= $user->getId().',';
            $post->setLikes($likes);
            $em->flush();
            return new JsonResponse(['likes'=>$likes]);
        }
        else {
            throw new \Exception('Estás tratando de hackearme?');
        }
        //return $this->render('$0.html.twig', []);
    }
}
