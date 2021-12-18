<?php

namespace App\Controller;

use App\Repository\MutationRepository;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Handle the endpoint of our mutation roots
 * each endpoint use exclusively sql to be faster than doing the query in sql and then in controller
 * We have indeed 15 billion mutation in DB
 */
class MutationController extends AbstractController
{
    
    public function __construct(MutationRepository $mutationRepository, EntityManagerInterface $em)
    {
        $this->mutationRepository = $mutationRepository;
        $this->em = $em;
    }

/**
 * Permet de récuprer le prix moyen par an par mois et par type (maison, appartement...)
 */
    public function getAveragePriceM2ByLocalCodeType(Request $request)
    {
        try{
            $local_type_code = (int) $request->query->get('local_type_code');
        }catch (Exception $e){
            return new Response("Impossible de récuépérer le 'local_type_code'",Response::HTTP_BAD_REQUEST); //BAD REQUEST ?
        }

        try{
            $mutation = $this->mutationRepository->AverageMeterSquarePriceByLocalCodeType($local_type_code);
            $response = new JsonResponse(['data' => $mutation]);
            $response->setStatusCode(Response::HTTP_OK);
            return $response;
        }catch(Exception $e){
            $response = new Response("Impossible de récupérer les informations",Response::HTTP_BAD_REQUEST); //BAD REQUEST ?
        }
    }


/**
 * Permet de récuprer le prix moyen par an par mois et par type (maison, appartement...)
 * NOM DE LA METHODE A CHANGER + ROUTE
 */
public function countMutationBetweenDate(Request $request)
{
    
    try{
        $startDate = self::createDate($request->query->get('startDate'));
       
    }catch (Exception $e){
        return new Response("Impossible de caster la date de début",Response::HTTP_BAD_REQUEST); //BAD REQUEST ?
    }
    try{
        $endDate = self::createDate($request->query->get('endDate'));
    }catch (Exception $e){
        return new Response("Impossible de caster la date de fin",Response::HTTP_BAD_REQUEST); //BAD REQUEST ?
    }
       $period = (int) $request->query->get('period');

        if ($period < 0 || $period > 3) {
            return new Response("La période n'est pas correcte",Response::HTTP_BAD_REQUEST); //BAD REQUEST ?
        }
    try{
        $mutation = $this->mutationRepository->MutationNumberBetweenTwoDatesByDayOrMonthOrYear($startDate,$endDate,$period);
        $response = new JsonResponse(['data' => $mutation]);
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    }catch(Exception $e){
        return new Response("Impossible de récupérer les informations",Response::HTTP_BAD_REQUEST); //BAD REQUEST ?
    }
}

function createDate($date)
{
    $word= "%20";
    if (strpos($date,$word) !== false)
    {
        $date = str_replace($word," ", $date);
    }
    $date = new \DateTime($date);
    return $date;
}

/**
 * Permet de récuprer le prix moyen par an par mois et par type (maison, appartement...)
 * NOM DE LA METHODE A CHANGER + ROUTE
 */
public function getNumberMutationByRegion(Request $request)
{
    try {
        $year = (int) $request->query->get('year');
    } catch(Exception $e) {
         return new Response("Impossible de récupérer l'année",Response::HTTP_BAD_REQUEST); //BAD REQUEST ?
    }

    try{
        $mutation = $this->mutationRepository->MutationByRegionByYear($year);
        $response = new JsonResponse(['data' => $mutation]);
        $response->setStatusCode(Response::HTTP_OK);
        return $response;
    } catch(Exception $e) {
        return new Response($e,Response::HTTP_BAD_REQUEST);
    }
}


    
}