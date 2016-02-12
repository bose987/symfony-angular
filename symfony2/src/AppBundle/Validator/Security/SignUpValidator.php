<?php
namespace AppBundle\Validator\Security;

use AppBundle\Validator\Validator;

class SignUpValidator extends Validator{
	
	public function __construct( $objForm, $objRequest ) {
		$this->setForm( $objForm );
		if( false == is_null( $objRequest ) ) {
			$this->setDataByRequest( $objRequest );
		}
	}
	
	public function validate() {
	
		$this->addEmail( 'email' );
		$this->addText( 'password' );
		$this->addText( 'name', 10 );
		
		$this->submit();
		$this->setErrors();
	}
}