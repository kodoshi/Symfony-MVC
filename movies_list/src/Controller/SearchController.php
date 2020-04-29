<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpClient\HttpClient;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search")
     * @param Request $request
     * @return Response
     * @throws \Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\DecodingExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface
     * @throws \Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface
     */
    public function search(Request $request): Response
    {
        $form = $this->createFormBuilder()
            ->add('searchfield', TextType::class, ['attr' => ['autofocus' => true]])
            ->add('type', ChoiceType::class, [
                'choices' => [
                    'Title' => 'title',
                    'Actor' => 'actor',
                    'Company' => 'company'
                ]               
            ])
            ->add('submit', SubmitType::class, ['label' => 'Search'])
            ->getForm()
        ;
        
        $form->handleRequest($request);
        $data = $form->getData();
        // dd($data['type']);
        // dd($form['searchfield']->getData());

        if ($form->isSubmitted() && $form->isValid()) {
            $client = HttpClient::create();

            if ($data['type'] == 'title'){
                $response = $client->request('GET', 'https://api.themoviedb.org/3/search/movie?api_key=86c110edf3bf002918c5a330307eb164&language=en-US&query='.$data['searchfield'].'&page=1&include_adult=false');
            }
            else if ($data['type'] == 'actor'){
                $response = $client->request('GET', 'https://api.themoviedb.org/3/search/person?api_key=86c110edf3bf002918c5a330307eb164&language=en-US&query='.$data['searchfield'].'&page=1&include_adult=false');
            }
            else if ($data['type'] == 'company'){
                $response = $client->request('GET', 'https://api.themoviedb.org/3/search/company?api_key=86c110edf3bf002918c5a330307eb164&query='.$data['searchfield'].'&page=1');
            }

            // $statusCode = $response->getStatusCode();
            // $content = $response->getContent();
            $content_array = $response->toArray();

            return $this->render('search/index.html.twig', [
                'searchForm' => $form->createView(),
                'result_search' => $content_array['results'],
            ]);
        }
        else{
            return $this->render('search/index.html.twig', [
                'searchForm' => $form->createView(),
                'movies' => $this->popular(),
            ]);
        }      
    }

    /**
     * @Route("/search", name="popular")
     */
    public function popular()
    {
        $client = HttpClient::create();
        $response = $client->request('GET', 'https://api.themoviedb.org/3/movie/popular?api_key=86c110edf3bf002918c5a330307eb164&language=en-US&page=1');

        // $statusCode = $response->getStatusCode();
        // $content = $response->getContent();
        $content_array = $response->toArray();

        return $content_array['results'];
    }
}
