<?php

namespace Site\FrontBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Site\FrontBundle\Entity\Article;
use Site\FrontBundle\Entity\Image;
use Site\FrontBundle\Form\ArticleType;
use Site\FrontBundle\Entity\Categorie;
use Site\FrontBundle\Form\CategorieType;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return $this->render('SiteFrontBundle:Default:index.html.twig');
    }

    /**
     * @Route("/Article", name="article_homepage")
     */
    public function articleAction()
    {
        $em = $this->getDoctrine()->getManager();
        $categories = $em->getRepository('SiteFrontBundle:Categorie')->findAll();
        return $this->render('SiteFrontBundle:Default:article.html.twig', ["categories"=>$categories]);
    }

    /**
     * @Route("/Article/{id}", name="article_getsingle", requirements={"id"="\d+"})
     */
    public function articleIdAction($id)
    {
        return $this->render('SiteFrontBundle:Default:articleId.html.twig', ["id"=>$id]);
    }

    /**
     * @Route("/Article/Add", name="article_add_form")
     */
    public function articleAddAction()
    {
        $article = new Article();
        $form = $this->createForm(new ArticleType(), $article);
        return $this->render('SiteFrontBundle:Default:ajout.html.twig', ['form'=>$form->createView()]);
    }

    /**
     * @Route("/Article/Update/{id}", name="article_update_form", requirements={"id"="\d+"})
     */
    public function articleUpdateAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $article = $em->getRepository('SiteFrontBundle:Article')->find($id);
        $form = $this->createForm(new ArticleType(), $article);
        return $this->render('SiteFrontBundle:Default:ajout.html.twig', ['form'=>$form->createView(), 'article'=>$article]);
    }

    /**
     * @Route("/Article/Save", name="article_save")
     */
    public function articleSaveAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->get('ref')){
            $article = $em->getRepository('SiteFrontBundle:Article')->find($request->get('ref'));
			$title_form = "Modifier";
        }else{
            $article = new Article();
			$title_form = "Ajouter";
        }
        $form = $this->createForm(new ArticleType(), $article);
        $form->handleRequest($request);
        if($form->isValid()){
            $image = new Image();
            $image->setFile($_FILES['file']);
            if($_FILES['file'] && $image->upload2($request)){
                if(!$request->get('ref')){
                    $article_tmp = $form->getData();
                    $article_tmp->setImage($image);
                    $em->persist($article_tmp);
                }else{
                    $article->setImage($image);
                }
                $em->persist($image);
                $em->flush();
            }else{
                if(!$request->get('ref')){
                    $article_tmp = $form->getData();
                    $em->persist($article_tmp);
                }
                $em->flush();
            }
            return  $this->redirect( $this->generateUrl('article_homepage'));
        }
        return $this->render('SiteFrontBundle:Default:ajout.html.twig', ['form'=>$form->createView(), 'article'=>$article, 'title_form'=>$title_form]); 
    }

    /**
     * @Route("/Article/Delete", name="article_delete")
     */
    public function articleDeleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->get('ref')){
            $article = $em->getRepository('SiteFrontBundle:Article')->find($request->get('ref'));
            $em->remove($article);
            $em->flush();
        }
        return  $this->redirect( $this->generateUrl('article_homepage'));
    }

    /**
     * @Route("/Categorie/Liste", name="categorie_liste")
     */
    public function categorieListeAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->get('ref')){
            $categorie = $em->getRepository('SiteFrontBundle:Categorie')->find($request->get('ref'));
			$title_form = "Modifier";
        }else{
            $categorie = new Categorie();
			$title_form = "Ajouter";
        }
        $form = $this->createForm(new CategorieType(), $categorie);
        $form->handleRequest($request);
        if($form->isValid()){
            if(!$request->get('id')){
                $categorie_tmp = $form->getData();
                $em->persist($categorie_tmp);
            }
            $em->flush();
            $categorie = new Categorie();
            $form = $this->createForm(new CategorieType(), $categorie);
			$title_form = "Ajouter";
        }
        $categories = $em->getRepository('SiteFrontBundle:Categorie')->findAll();
        return $this->render('SiteFrontBundle:Default:categorieListe.html.twig', ['form'=>$form->createView(), 'categorie'=>$categorie, 'categories'=>$categories, 'title_form'=>$title_form]);
    }

    /**
     * @Route("/Categorie/Delete", name="categorie_delete")
     */
    public function categorieDeleteAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        if($request->get('ref')){
            $categorie = $em->getRepository('SiteFrontBundle:Categorie')->find($request->get('ref'));
            $em->remove($categorie);
            $em->flush();
        }
        return  $this->redirect( $this->generateUrl('categorie_liste'));
    }
    
}
