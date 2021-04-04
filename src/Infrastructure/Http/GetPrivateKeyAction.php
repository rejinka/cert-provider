<?php
declare(strict_types = 1);

namespace App\Infrastructure\Http;

use App\Application\PrivateKeyProvider;
use App\Domain\Exception\EntityNotFoundException;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

#[Route(path: "/{resolver}/{domain}/privkey.pem")]
final class GetPrivateKeyAction implements Action
{

    public function __construct(private PrivateKeyProvider $provider) { }

    public function __invoke(string $resolver, string $domain): Response
    {
        try {
            return (new Response())
                ->setContent(call_user_func($this->provider, $resolver, $domain));
        } catch (EntityNotFoundException $e) {
            throw new NotFoundHttpException($e->getMessage(), $e);
        }
    }

}
