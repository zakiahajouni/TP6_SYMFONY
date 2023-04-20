<?php

namespace App\Controller;
use App\Entity\Article;
use App\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\Category;
use App\Form\CategoryType;

class IndexController extends AbstractController
{
    /**
     *@Route("/",name="article_list")
     */
    public function home(ManagerRegistry $doctirine)
    {
        $entityManager = $doctirine->getManager();
        //récupérer tous les articles de la table article de la BD
        // et les mettre dans le tableau $articles
        $articles = $doctirine->getRepository(Article::class)->findAll();
        return $this->render('articles/index.html.twig', ['articles' => $articles]);
    }

    /**
     * @Route("/article/save")
     */
    public function save(ManagerRegistry $doctirine)
    {
        $entityManager = $doctirine->getManager();
        $article = new Article();
        $article->setNom('Article 1');
        $article->setPrix(2500);
        $entityManager->persist($article);
        $entityManager->flush();
        return new Response('Saved an article with the id of ' . $article->getId());
    }
/**
 * @Route("/article/new", name="new_article")
 * Method({"GET", "POST"})
 */
public function new(Request $request,ManagerRegistry $doctirine) {
    $entityManager = $doctirine->getManager();
    $article = new Article();
    $form = $this->createForm(ArticleType::class,$article);
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
    $article = $form->getData();
    $entityManager = $doctirine->getManager();
    $entityManager->persist($article);
    $entityManager->flush();
    return $this->redirectToRoute('article_list');
    }
    return $this->render('articles/new.html.twig',['form' => $form->createView()]);
    }
   
    /**
 * @Route("/article/{id}", name="article_show")
 */
 public function show($id,ManagerRegistry $doctirine) {
    $entityManager = $doctirine->getManager();
    $article = $doctirine->getRepository(Article::class)->find($id);
    return $this->render('articles/show.html.twig',
    array('article' => $article));
     }
/**
 * @Route("/article/edit/{id}", name="edit_article")
 * Method({"GET", "POST"})
 */
public function edit(Request $request, $id,ManagerRegistry $doctirine) {
    $entityManager = $doctirine->getManager();
    $article = new Article();
   $article =  $doctirine->getRepository(Article::class)->find($id);
   
    $form = $this->createForm(ArticleType::class,$article);
   
    $form->handleRequest($request);
    if($form->isSubmitted() && $form->isValid()) {
   
    $entityManager =  $doctirine->getManager();
    $entityManager->flush();
   
    return $this->redirectToRoute('article_list');
    }
   
    return $this->render('articles/edit.html.twig', ['form' =>
   $form->createView()]);
    }
    /**
 * @Route("/article/delete/{id}",name="delete_article")
 * @Method({"DELETE"})
  */
  public function delete(Request $request, $id,ManagerRegistry $doctirine)
  {
      $entityManager = $doctirine->getManager();
    $article = $doctirine->getRepository(Article::class)->find($id);
   
    $entityManager = $doctirine->getManager();
    $entityManager->remove($article);
    $entityManager->flush();
   
    $response = new Response();
    $response->send();
    return $this->redirectToRoute('article_list');
    }
/**
 * @Route("/category/newCat", name="new_category")
 * Method({"GET", "POST"})
 */
#[Route('/category/newCat', name: 'new_category')]
public function newCategory(Request $request, ManagerRegistry $doctrine)
{
    $category = new Category();
    $form = $this->createForm(CategoryType::class, $category);
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $article = $form->getData();
        $entityManager = $doctrine->getManager();
        $entityManager->persist($category);
        $entityManager->flush();
        
    }
    
    return $this->render('articles/newCategory.html.twig', ['form' =>$form->createView()]);
}





    }