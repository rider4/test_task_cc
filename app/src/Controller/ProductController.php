<?php

namespace App\Controller;

use App\Dto\Product\QueryDto;
use App\Service\ProductSearcher;
use App\Service\Validator\Product\QueryDataValidator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products', methods: Request::METHOD_GET/*, format: 'json'*/)]
    public function index(
        ProductSearcher    $productSearcher,
        QueryDataValidator $validator,
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST,)]
        QueryDto           $dto = new QueryDto(),
    ): JsonResponse
    {
        $simplifiedViolations = $validator->validate($dto);
        if ($simplifiedViolations) {
            return $this->json(['errors' => $simplifiedViolations], Response::HTTP_BAD_REQUEST);
        }

        return $this->json($productSearcher->search($dto));
    }
}
