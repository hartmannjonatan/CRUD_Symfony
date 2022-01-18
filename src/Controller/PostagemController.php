<?php

namespace App\Controller;

use App\Entity\Postagem;
use App\Form\PostagemType;
use App\Form\PostagemTypeEdit;
use App\Repository\PostagemRepository;
use App\Entity\Tag;
use App\Repository\TagRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/postagem')]
class PostagemController extends AbstractController
{
    #[Route('/create', name: 'postagem-create')]
    public function create(Request $request, ManagerRegistry $doctrine, PostagemRepository $postagemRepository, CategoriaRepository $categoriaRepository): Response {
        $verif = true;

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(180);

        $categoriasMenu = $categoriaRepository
            ->findAllCategory(5);
        
        if(!$categoriasMenu){
            $verif = false;
            $this->addFlash(
                'error',
                'Não há categorias cadastradas, crie uma e após escreva sua postagem!'
            );
            
            return $this->redirectToRoute('categoria-create');
        }

        $entityManager = $doctrine->getManager();

        $postagem = new Postagem();

        $form = $this->createForm(PostagemType::class, $postagem);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $postagem = $form->getData();
            $postagem->setCreatedAt();
            $postagem->setUpdatedAt();

            if(!$form->get('author')->getData()){
                $postagem->setAuthor();
            } else {
                $postagem->setAuthor($form->get('author')->getData());
            }

            $arrayTags = explode(';', $form->get('tag')->getData());

            foreach($arrayTags as $value){
                $tag = new Tag();
                $tag->setNametag($value);
                $entityManager->persist($tag);
                $postagem->addTag($tag);
            }

            try {
                $entityManager->persist($postagem);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'O slug "'.$postagem->getSlugText().'" já existe. Tente outro...'
                );
                
                return $this->redirectToRoute('postagem-create');
            }
            

            $this->addFlash(
                'success',
                'A postagem "'.$postagem->getTitulo().'" foi criada com sucesso!'
            );
            
            return $this->redirectToRoute('posts');
        }

        return $this->renderForm('blog/create.html.twig', [
            'form' => $form,
            'type' => 'Postagem',
            'categoriasMenu' => $categoriasMenu,
            'verificador' => $verif
        ]);
    }

    #[Route('/read/{slug}', name: 'postagem-read')]
    public function read(string $slug, Request $request, ManagerRegistry $doctrine, PostagemRepository $postagemRepository, CategoriaRepository $categoriaRepository): Response {
        $verif = true;

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(180);

        $categoriasMenu = $categoriaRepository
            ->findAllCategory(5);
        
        if(!$categoriasMenu){
            $verif = false;
            
            return $this->redirectToRoute('categoria-create');
        }

        $entityManager = $doctrine->getManager();

        $postagem = $postagemRepository
            ->findSlug($slug);

        $postagem = $postagem[0];

        $tags = explode(';', $postagem['tag']);
        

        return $this->render('blog/postagemRead.html.twig', [
            'postagem' => $postagem,
            'tags' => $tags,
            'categoriasMenu' => $categoriasMenu,
            'verificador' => $verif
        ]);
    }

    #[Route('/delete/{id}', name: 'postagem-delete')]
    public function delete(int $id, Request $request, ManagerRegistry $doctrine, CategoriaRepository $categoriaRepository, PostagemRepository $postagemRepository): Response {
        $verif = true;

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(180);

        $categoriasMenu = $categoriaRepository
            ->findAllCategory(5);
        
        if(!$categoriasMenu){
            $verif = false;
        }

        $entityManager = $doctrine->getManager();
        $postagem = $postagemRepository
            ->find($id);

        if(!$postagem){
            $this->addFlash(
                'error',
                'Essa postagem não existe. :('
            );
            
            return $this->redirectToRoute('posts');
        }

            $this->addFlash(
                'success',
                'A postagem "'.$postagem->titulo.'" foi deletada com sucesso!'
            );

            $entityManager->remove($postagem);
            $entityManager->flush();
            
            
            return $this->redirectToRoute('posts');
    }

    #[Route('/update/{id}', name: 'postagem-update')]
    public function update(int $id, Request $request, ManagerRegistry $doctrine, CategoriaRepository $categoriaRepository, PostagemRepository $postagemRepository): Response {
        $verif = true;

        $response = new Response();
        $response->setPublic();
        $response->setMaxAge(180);

        $categoriasMenu = $categoriaRepository
            ->findAllCategory(5);
        
        if(!$categoriasMenu){
            $verif = false;
        }

        $entityManager = $doctrine->getManager();
        $postagem = $entityManager->getRepository(Postagem::class)->find($id);

        if(!$postagem){
            $this->addFlash(
                'error',
                'Essa postagem não existe. :('
            );
            
            return $this->redirectToRoute('posts');
        }

        $form = $this->createForm(PostagemTypeEdit::class, $postagem);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $postagem = $form->getData();
            $postagem->setUpdatedAt();

            if(!$form->get('author')->getData()){
                $postagem->setAuthor();
            } else {
                $postagem->setAuthor($form->get('author')->getData());
            }

            $arrayTags = explode(';', $form->get('tag')->getData());

            foreach($arrayTags as $value){
                $tag = new Tag();
                $tag->setNametag($value);
                $entityManager->persist($tag);
                $postagem->addTag($tag);
            }

            try {
                $entityManager->persist($postagem);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'O slug "'.$postagem->getSlugText().'" já existe. Tente outro...'
                );
                
                return $this->redirectToRoute('postagem-update');
            }
            

            $this->addFlash(
                'success',
                'A postagem "'.$postagem->getTitulo().'" foi editada com sucesso!'
            );
            
            return $this->redirectToRoute('posts');
        }

        return $this->renderForm('blog/edit.html.twig', [
            'form' => $form,
            'type' => 'Postagem',
            'categoriasMenu' => $categoriasMenu,
            'verificador' => $verif
        ]);
    }
}
