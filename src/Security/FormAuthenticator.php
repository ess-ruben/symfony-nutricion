<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationSuccessHandlerInterface;
use Symfony\Component\Security\Http\HttpUtils;

class FormAuthenticator implements AuthenticationSuccessHandlerInterface
{
    private $httpUtils;

    public function __construct(HttpUtils $httpUtils)
    {
        $this->httpUtils = $httpUtils;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token):Response
    {
        // Aquí puedes agregar la lógica que desees realizar al tener éxito en la autenticación
        // Por ejemplo, redireccionar a una página específica después del inicio de sesión exitoso
        $response = new RedirectResponse($this->httpUtils->generateUri($request, 'admin'));

        return $response;
    }
}
