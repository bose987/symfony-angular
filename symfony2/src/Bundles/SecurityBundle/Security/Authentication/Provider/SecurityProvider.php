<?php 
namespace Bundles\SecurityBundle\Security\Authentication\Provider;

use Symfony\Component\Security\Core\Authentication\Provider\AuthenticationProviderInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Bundles\SecurityBundle\Security\Authentication\Token\SecurityToken;

class SecurityProvider implements AuthenticationProviderInterface
{
	private $userProvider;
	private $cacheDir;

	public function __construct(UserProviderInterface $userProvider, $cacheDir)
	{
		$this->userProvider = $userProvider;
		$this->cacheDir     = $cacheDir;
	}

	public function authenticate(TokenInterface $token )
	{
		if( $token->getRequestToken() === $token->getAuthToken() ) {
			return $token;
		}

		throw new AuthenticationException('The Authentication failed.');
	}
	
	public function supports(TokenInterface $token)
	{
		return $token instanceof SecurityToken;
	}
}