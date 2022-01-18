<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use App\Entity\Postagem;
use App\Repository\PostagemRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class BlogController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(CategoriaRepository $categoriaRepository): Response
    {
        $verif = true;

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(180);

        $categoriasMenu = $categoriaRepository
            ->findAllCategory(5);
        
        if(!$categoriasMenu){
            $verif = false;
        }

        return $this->render('homepage/index.html.twig', [
            'categoriasMenu' => $categoriasMenu,
            'verificador' => $verif
        ], $response);
    }

    /**
     * @Route("/postagens", name="posts")
     */
    public function show_posts(CategoriaRepository $categoriaRepository, PostagemRepository $postagemRepository): Response
    {
        $verif = true;

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(180);

        $categoriasMenu = $categoriaRepository
            ->findAllCategory(5);
        
        if(!$categoriasMenu){
            $verif = false;
        } 

        $postagens = $postagemRepository
            ->findAllPostagens();

        return $this->render('blog/postagens.html.twig', [
            'postagens' => $postagens,
            'categoriasMenu' => $categoriasMenu,
            'verificador' => $verif
        ], $response);
        
    }

    /**
     * @Route("/categorias", name="categorias")
     */
    public function show_categorias(CategoriaRepository $categoriaRepository): Response
    {
        $verif = true;

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(180);

        $categorias = $categoriaRepository
            ->findAllCategory();
        
        if(!$categorias){
            $verif = false;
            $categoriasMenu = null;
        } else{

            $categoriasMenu = $categoriaRepository
            ->findAllCategory(5);

        }

        return $this->render('blog/categorias.html.twig', [
            'categorias' => $categorias,
            'categoriasMenu' => $categoriasMenu,
            'verificador' => $verif
        ], $response);

    }
}
