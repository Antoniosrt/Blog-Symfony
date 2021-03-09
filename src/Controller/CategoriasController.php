<?php

namespace App\Controller;

use App\Entity\Categorias;
use App\Form\CadastroCategoriaType;
use App\Repository\CategoriasRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CategoriasController extends AbstractController
{
    /**
     * @Route("/categoria/newCategoria", name="categoria")
     */
    public function index(Request $request,EntityManagerInterface $em, CategoriasRepository $categoriasRepository): Response
    {
        $categoria=new Categorias();
        $cadastro=$this->createForm(CadastroCategoriaType::class,$categoria);

            $cadastro->handleRequest($request);
        $categorias=$categoriasRepository->findAll();
        if ($cadastro->isSubmitted() && $cadastro->isValid()){
            $em->persist($categoria);
            $em->flush();
            return $this->redirectToRoute('categoria');
        }

        return $this->render('categorias/index.html.twig', [
            'form_cat'=>$cadastro->createView(),
            'categorias'=>$categorias
        ]);
    }
    /**
     * @Route("/categoria/lista_categoria")
     */
    public function list(CategoriasRepository $categoriasRepository)
    {
        $categoria=$categoriasRepository->findAll();
        return $this->render('categorias/list.html.twig',[
            'categorias'=>$categoria
        ]);
    }

    public function menu(CategoriasRepository $categoriasRepository)
    {
        $categoria=$categoriasRepository->findAll();
        return $this->render('base.html.twig',[
            'categorias'=>$categoria
        ]);
    }
    /**
     * @Route("/delete/{id}",methods={"DELETE"})
     *
     *
     */
    public function delete(Request $request,$id,CategoriasRepository $categoriasRepository,EntityManagerInterface $entityManager){
        $categoria=$categoriasRepository->find($id);
        $entityManager->remove($categoria);
        $entityManager->flush();
        $response=new Response();
        $response->send();
    }
}
