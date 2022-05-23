<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Http\Authorization\AccessDeniedHandlerInterface;

class AccessDeniedHandler implements AccessDeniedHandlerInterface
{
    public function handle(Request $request, AccessDeniedException $accessDeniedException): ?Response
    {
        $content = '<br> <br> <br><br> <br> <br> <br>  <br> <br> <br> <br> 
        <center> 
        <h1>Accès refusé: vous n\'avez pas l\'autorisation d\'effectuer cette opération </h1>
        </center>';
        return new Response($content, 403);
    }
}
