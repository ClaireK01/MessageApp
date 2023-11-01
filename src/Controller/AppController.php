<?php

namespace App\Controller;

use phpDocumentor\Reflection\Types\Boolean;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\VarDumper\VarDumper;

class AppController extends AbstractController
{
    #[Route('/', name: 'app_app')]
    public function index(): Response
    {

        return $this->render('app/index.html.twig', [
            'controller_name' => 'AppController',
        ]);
    }

    #[Route('/view-message', name: 'app_view_messages', methods: ['POST', 'GET'])]
    public function getMessageView(Request $request){

        $oldMsg = $request->get('old') ?? '';
        $message = $request->get('mess') ?? '';
        $from = $request->get('from') == "true";


        $view = $this->renderView('app/view-messages.html.twig', [
            'oldmsg' => $oldMsg,
            'message' => $message,
            'from' => $from,
        ]);

        if($request->isXmlHttpRequest()){
            return $this->json(['view' => $view]);
        }

        return $this->render('app/view-messages.html.twig', [
            'oldmsg' => $oldMsg,
            'message' => $message,
            'from' => $from,
        ]);
    }
}
