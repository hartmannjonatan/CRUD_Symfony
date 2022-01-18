<?php

namespace App\Controller;

use App\Repository\PostagemRepository;
use Doctrine\Persistence\ManagerRegistry;
use App\Repository\CategoriaRepository;
use App\Entity\Categoria;
use App\Form\Type\CategoriaType;
use App\Form\Type\CategoriaTypeEdit;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categoria')]
class CategoriaController extends AbstractController
{
    #[Route('/create', name: 'categoria-create')]
    public function create(Request $request, ManagerRegistry $doctrine, CategoriaRepository $categoriaRepository): Response {
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

        $categoria = new Categoria();

        $form = $this->createForm(CategoriaType::class, $categoria);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $categoria = $form->getData();
            $categoria->setCreatedAt();
            $categoria->setUpdatedAt();
            try {
                $entityManager->persist($categoria);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'O slug "'.$categoria->getSlugText().'" já existe. Tente outro...'
                );
                
                return $this->redirectToRoute('categoria-create');
            }
            

            $this->addFlash(
                'success',
                'A categoria "'.$categoria->getName().'" foi criada com sucesso!'
            );
            
            return $this->redirectToRoute('categorias');
        }

        return $this->renderForm('blog/create.html.twig', [
            'form' => $form,
            'type' => 'Categoria',
            'categoriasMenu' => $categoriasMenu,
            'verificador' => $verif
        ]);
    }

    #[Route('/update/{id}', name: 'categoria-update')]
    public function update(int $id, Request $request, ManagerRegistry $doctrine, CategoriaRepository $categoriaRepository): Response {
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
        $categoria = $entityManager->getRepository(Categoria::class)->find($id);

        if(!$categoria){
            $this->addFlash(
                'error',
                'Essa categoria não existe. :('
            );
            
            return $this->redirectToRoute('categorias');
        }

        $form = $this->createForm(CategoriaTypeEdit::class, $categoria);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            $categoria = $form->getData();
            $categoria->setUpdatedAt();
            try {
                $entityManager->persist($categoria);
                $entityManager->flush();
            } catch (\Exception $e) {
                $this->addFlash(
                    'error',
                    'O slug "'.$categoria->getSlugText().'" já existe. Tente outro...'
                );
                
                return $this->redirectToRoute('categoria-update');
            }
            

            $this->addFlash(
                'success',
                'A categoria "'.$categoria->getName().'" foi editada com sucesso!'
            );
            
            return $this->redirectToRoute('categorias');
        }

        return $this->renderForm('blog/edit.html.twig', [
            'form' => $form,
            'type' => 'Categoria',
            'categoriasMenu' => $categoriasMenu,
            'verificador' => $verif
        ]);
    }

    #[Route('/delete/{id}', name: 'categoria-delete')]
    public function delete(int $id, Request $request, ManagerRegistry $doctrine, CategoriaRepository $categoriaRepository): Response {
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
        $categoria = $entityManager->getRepository(Categoria::class)->find($id);

        if(!$categoria){
            $this->addFlash(
                'error',
                'Essa categoria não existe. :('
            );
            
            return $this->redirectToRoute('categorias');
        }

            $this->addFlash(
                'success',
                'A categoria "'.$categoria->getName().'" foi deletada com sucesso!'
            );

            $entityManager->remove($categoria);
            $entityManager->flush();
            
            
            return $this->redirectToRoute('categorias');
    }

    #[Route('/read/{slug}', name: 'categoria-read')]
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

        $categoria = $categoriaRepository
            ->findSlug($slug);

        $categoria = $categoria[0];

        $postagens = $postagemRepository
            ->findAllByCategoria($categoria['id']);

        return $this->render('blog/categoriaRead.html.twig', [
            'postagens' => $postagens,
            'categoria' => $categoria,
            'categoriasMenu' => $categoriasMenu,
            'verificador' => $verif
        ]);
    }
}
