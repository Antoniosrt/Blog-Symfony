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
    public function index(Request $request,EntityManagerInterface $em,SluggerInterface $slugger,CategoriasRepository $categoriasRepository): Response
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
        $categoria=$categoriasRepository->findAll();
        return $this->render('post/index.html.twig', [
            'form_post' => $form->createView(),
            'categorias'=>$categoria
        ]);
    }

    /**
     *
     * @Route ("/post/edit/{id}")
     */
    public function edit($id,Request $request,EntityManagerInterface $em,SluggerInterface $slugger,CategoriasRepository $categoriasRepository, PostRepository $postRepository) : Response
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
        $categoria=$categoriasRepository->findAll();
        return $this->render('post/edit.html.twig', [
            'form_post' => $form->createView(),
            'categorias'=>$categoria
        ]);
    }
    /**
     * @Route("post/delete/{id}",methods={"DELETE","GET"})
     */
    public function delete(Request $request,EntityManagerInterface $em, PostRepository $postRepository,$id) {


        $post=$postRepository->find($id);
        $em->remove($post);
        $em->flush();
        $response=new Response();
        $response->send(true);
    }

    /**
     * @Route("/",name="home")
     */
    public function list(PostRepository $postRepository, CategoriasRepository $categoriasRepository)
    {
        $post=$postRepository->findAll();
        $categoria=$categoriasRepository->findAll();
        return $this->render('blog/blog.html.twig',[
            'posts'=>$post,
            'categorias'=>$categoria
        ]);
    }

    /**
     * @return Response
     * @Route("/{id}")
     */
    public function verCategorias(PostRepository $postRepository, CategoriasRepository $categoriasRepository,$id){
          /*  $em=$this->getDoctrine()->getManager();
            $query=$em->createQuery(
                'SELECT nome FROM post WHERE cat_id = :id '
            )->setParameter('*',$id);
        $products = $query->getResult();
        */
        $categoria=$categoriasRepository->findAll();
        $catnome=$categoriasRepository->find($id);
        $repository = $this->getDoctrine()
            ->getRepository('App:Post');

        $query = $repository->createQueryBuilder('post')
            ->where('post.cat = :cat')
            ->setParameter('cat', $id)
            ->getQuery();
        $post = $query->getResult();
        return $this->render('blog/blogfilter.html.twig',[
            'posts'=>$post,
            'categorias'=>$categoria,
            'catnome'=>$catnome
        ]);
    }
    /**
     * @Route("/post/view/{id}",name="viewpost")
     */
    public function viewpost(PostRepository $postRepository, CategoriasRepository $categoriasRepository,$id)
    {
        $post=$postRepository->find($id);
        $categoria=$categoriasRepository->findAll();
        return $this->render('post/view.html.twig',[
            'posts'=>$post,
            'categorias'=>$categoria
        ]);
    }

    /**
     * @param PostRepository $postRepository
     * @return Response
     * @Route ("/post/table",name="table")
     */
    public function table(PostRepository $postRepository,CategoriasRepository  $categoriasRepository): Response
    {
        $post=$postRepository->findAll();
        $categoria=$categoriasRepository->findAll();
        return $this->render('post/table.html.twig',[
            'posts'=>$post,
            'categorias'=>$categoria
        ]);
    }



        }
