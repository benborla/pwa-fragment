<?php

namespace App\Controller;

use App\DataSource\Question;
use App\DataSource\Random;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;

class SecuredController extends AbstractController
{
    /**
     * @Route("/ajax/refresh-token")
     */
    public function refreshToken(JWTTokenManagerInterface $JWTManager)
    {
        $user = $this->getUser();

        if (null === $user) {
            return new JsonResponse(['message' => 'JWT Token not found'], 401);
        }
        return new JsonResponse([
            'token' => $JWTManager->create($user)
        ]);
    }

    /**
     * @Route("/ajax/form-example/respond")
     */
    public function pageFormExampleResponse(Request $request, TranslatorInterface $translator, Question $question)
    {
        $domain = $request->query->get('domain', 'default');
        $name = $request->request->get('name', '');
        $posted_question = $request->request->get('question', '');

        if ($question->identifyQuestion($posted_question)) {
            $answerResponse = $question->answerQuestion($posted_question);

            return new JsonResponse([
                'status' => 1,
                'answer' => $translator->trans($answerResponse, [], $domain),
            ]);
        } else {
            return new JsonResponse([
                'status' => 0,
                'error' => [
                    'message' => $translator->trans('example.form.no_question', ['%name%' => $name], $domain),
                ]
            ]);
        }
    }

    /**
     * @Route("/ajax/eightball/answer")
     */
    public function pageEightballAnswer(Request $request, TranslatorInterface $translator, Random $random)
    {
        $domain = $request->query->get('domain', 'default');

        $result = $random->get(0, 19);

        return new JsonResponse([
            'answer' => $translator->trans("example.eightball.results.$result", [], $domain)
        ]);
    }
}
