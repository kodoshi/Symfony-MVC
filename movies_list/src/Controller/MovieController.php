<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpClient\HttpClient;

class MovieController extends AbstractController
{
    /**
     * @Route("/movie/{id}", name="movie")
     * @param $id
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function movie($id)
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/'.$id.'?api_key=86c110edf3bf002918c5a330307eb164&language=en-US');
        
        $statusCode = $response->getStatusCode();
        $content = $response->getContent();
        $content_array = $response->toArray();

        return $this->render('movie/index.html.twig',[
            'movie'=>$content_array
        ]);
    }


}
