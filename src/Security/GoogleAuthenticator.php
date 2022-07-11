<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use KnpU\OAuth2ClientBundle\Client\ClientRegistry;
use KnpU\OAuth2ClientBundle\Security\Authenticator\OAuth2Authenticator;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\Security\Http\Authenticator\Passport\SelfValidatingPassport;

class GoogleAuthenticator extends OAuth2Authenticator
{
    private EntityManagerInterface $entityManager;

    private UrlGeneratorInterface $urlGenerator;

    private ClientRegistry $client;

    public function __construct(EntityManagerInterface $entityManager, UrlGeneratorInterface $urlGenerator, ClientRegistry $client)
    {
        $this->entityManager = $entityManager;
        $this->urlGenerator = $urlGenerator;
        $this->client = $client;
    }

    public function supports(Request $request): bool
    {
        return 'connect_google_check' === $request->attributes->get('_route');
    }

    public function authenticate(Request $request): SelfValidatingPassport
    {
        $client = $this->client->getClient('google');

        $accessToken = $client->getAccessToken();
        $googleUser = $client->fetchUserFromToken($accessToken);
        $request->getSession()->set(Security::LAST_USERNAME, $googleUser->getId());

        return new SelfValidatingPassport(
            new UserBadge((string) $googleUser->getId(), function () use ($googleUser) {
                $existingUser = $this->entityManager->getRepository(User::class)->findOneBy([
                    'googleId' => (string) $googleUser->getId(),
                ]);

                if ($existingUser) {
                    return $existingUser;
                }

                /** @var array{name: string, picture: string} */
                $data = $googleUser->toArray();

                // Create user if it does not exist
                $user = new User();
                $user->setGoogleId((string) $googleUser->getId());
                $user->setName($data['name']);
                $user->setPicture($data['picture']);

                $this->entityManager->persist($user);
                $this->entityManager->flush();

                return $user;
            })
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        // For example:
        // return new RedirectResponse($this->urlGenerator->generate('some_route'));
        // throw new \Exception('TODO: provide a valid redirect inside '.__FILE__);
        return new RedirectResponse($this->urlGenerator->generate('admin'));
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $message = strtr($exception->getMessageKey(), $exception->getMessageData());

        return new Response($message, Response::HTTP_FORBIDDEN);
    }
}
