<?php

namespace App\Controller;

use App\Entity\Categorias;
use App\Entity\Post;
use App\Form\CadastroCategoriaType;
use App\Form\CadastroPostType;
use App\Repository\CategoriasRepository;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\String\Slugger\SluggerInterface;

class PostController extends AbstractController
{
    /**
     * @Route("/post/post_create", name="post_create",methods={"POST","GET"})
     */
    public function index(Request $request,EntityManagerInterface $em,SluggerInterface $slugger): Response
    {
        $post=new Post();
        $form=$this->createForm(CadastroPostType::class,$post);

        $form->handleRequest($request);

        if($form->isSubmitted()){
            $file=$form->get('Imagem')->getData();
            $upload_directory=$this->getParameter('uploads_directory');
            $safefile_name=$slugger->slug($file);
            $filename= $safefile_name.uniqid().'.'.$file->guessExtension();
            $file->move(
                $upload_directory,
                $filename
            );
            $post->setImagem($filename);
            $em->persist($post);
            $em->flush();

            return $this->redirectToRoute('home');
        }
        if ($form->isSubmitted() && !$form->isValid()) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST);
        }

        return $this->render('post/index.html.twig', [
            'form_post' => $form->createView()
        ]);
    }

    /**
     *
     * @Route ("/post/edit/{id}")
     */
    public function edit($id,Request $request,EntityManagerInterface $em,SluggerInterface $slugger, PostRepository $postRepository) : Response
    {

        $post = $postRepository->find($id);
        $form = $this->createForm(CadastroPostType::class, $post);

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $file = $form->get('Imagem')->getData();

                $upload_directory = $this->getParameter('uploads_directory');
                $safefile_name = $slugger->slug($file);

                $filename = $safefile_name . uniqid() . '.' . $file->guessExtension();
                $file->move(
                    $upload_directory,
                    $filename
                );
                $post->setImagem($filename);


        $em->flush();

        return $this->redirectToRoute('table');
    }
        if ($form->isSubmitted() && !$form->isValid()) {
            throw new HttpException(Codes::HTTP_BAD_REQUEST);
        }

        return $this->render('post/edit.html.twig', [
            'form_post' => $form->createView()
        ]);
    }
    /**
     * @param Request $request
     * @param EntityManagerInterface $em
     * @Route("/delete/{id}",methods={"DELETE"})
     */
    public function delete(Request $request,EntityManagerInterface $em, PostRepository $postRepository,$id) {
        $post=$postRepository->find($id);
        $em->remove($post);
        $em->flush();
        $response=new Response();
        $response->send();
    }

    /**
     * @Route("/",name="home")
     */
    public function list(PostRepository $postRepository, CategoriasRepository $categoriasRepository)
    {
        $post=$postRepository->findAll();
        return $this->render('blog/blog.html.twig',[
            'posts'=>$post
        ]);
    }

    /**
     * @param PostRepository $postRepository
     * @return Response
     * @Route ("/post/table",name="table")
     */
    public function table(PostRepository $postRepository): Response
    {
        $post=$postRepository->findAll();
        return $this->render('post/table.html.twig',[
            'posts'=>$post
        ]);
    }



        }
