<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Category;
use App\Entity\Program;

class CategoryController extends AbstractController
{
    #[Route('/category', name: 'category')]
    public function index(): Response
    {
        $categories = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findAll();
        return $this->render('category/index.html.twig', [
            'category' => $categories,
        ]);
    }

    #[Route('/category/show/{categoryName}', name: 'category_show')]
    public function show(string $categoryName) :Response {
        $category = $this->getDoctrine()
            ->getRepository(Category::class)
            ->findOneBy(['name' => mb_strtolower($categoryName)]);
        $programs = $this->getDoctrine()
            ->getRepository(Program::class)
            ->findBy(['category'=> $category], ['id' => 'DESC'], 3);
        if (!$category) {
            throw $this->createNotFoundException(
                'No programs with category ' . $categoryName . ', found in program table.'
            );
        }

        return $this->render('category/show.html.twig', [
            'programs' => $programs,
            'categoryName'  => $categoryName
        ]);
    }
}
