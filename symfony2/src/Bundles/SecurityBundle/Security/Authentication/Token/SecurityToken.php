<?php 
namespace Bundles\SecurityBundle\Security\Authentication\Token;
use Symfony\Component\Security\Core\Authentication\Token\AbstractToken;
use Symfony\Component\Security\Core\Util\SecureRandom;

class SecurityToken extends AbstractToken{
	
	private $strAuthToken;
	private $strRequestToken;
	private $strSessionToken;
	
	public function __construct(array $roles = array())
	{
		parent::__construct($roles);
	
		// If the user has roles, consider it authenticated
		$this->setAuthenticated(count($roles) > 0);
		$this->setSessionToken();
	}
	
	public function getCredentials()
	{
		return '';
	}
	
	public function generateAuthToken( $arrmixAuthData ) {
		
		if( false == is_array( $arrmixAuthData ) ) return;
		
		$strConcat = '';
		foreach( $arrmixAuthData as $strAuthData ) {
			$strConcat .= $strAuthData;
		}
		$this->token = md5( $strConcat );
	}

	public function getAuthToken() {
		return $this->token;
	}

	public function getRequestToken() {
		return $this->strRequestToken;
	}
    
    public function setRequestToken( $strRequestToken ) {
    	$this->strRequestToken = $strRequestToken;
    }
    
    private function setSessionToken() {
    	$generator = new SecureRandom();
    	$this->strSessionToken =  $generator->nextBytes(10);
    }
    public function getSessionToken()
    {
    	return $this->strSessionToken;
    }
}
