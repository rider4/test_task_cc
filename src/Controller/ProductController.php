<?php

namespace App\Controller;

use App\Dto\Product\QueryDto;
use App\Dto\Product\Response\OfferDto;
use App\Dto\Product\Response\PriceDto;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapQueryString;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Validation;

class ProductController extends AbstractController
{
    #[Route('/products', name: 'app_products', methods: Request::METHOD_GET, format: 'json')]
    public function index(
        #[MapQueryString(validationFailedStatusCode: Response::HTTP_BAD_REQUEST,)] QueryDto $dto = new QueryDto(),

    ): JsonResponse
    {
        $simplifiedViolations = $this->validInputDto($dto);
        if ($simplifiedViolations) {
            return $this->json(['errors' => $simplifiedViolations], Response::HTTP_BAD_REQUEST);
        }

        $res = [
            (new OfferDto('sku', 'name', 'category', (new PriceDto(100, 200, '2%'))))
        ];
        return $this->json($res);


        return $this->json([
            'message' => 'Welcome to your new controller!',
            'path' => 'src/Controller/ProductController.php',
        ]);
    }

    /**
     * @param QueryDto $dto
     * @return array
     */
    public function validInputDto(QueryDto $dto): array
    {
        $validator = Validation::createValidatorBuilder()
            ->addMethodMapping('lazyLoadValidatorMetadata')
            ->getValidator();
        $violationList = $validator->validate($dto);

        $errors = [];
        if ($violationList->count()) {
            foreach ($violationList as $violation) {
                $errors[$violation->getPropertyPath()][] = $violation->getMessage();
            }
        }

        return $errors;
    }
}
