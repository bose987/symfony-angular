<?php
namespace AppBundle\Validator\Security;

use AppBundle\Validator\Validator;


class LoginValidator extends Validator{
	
	public function __construct( $objForm, $objRequest ) {
		$this->setForm( $objForm );
		if( false == is_null( $objRequest ) ) {
			$this->setDataByRequest( $objRequest );
		}
	}
	
	public function validate() {
		$this->addEmail( 'email' );
		$this->addText( 'password' );
		
		$this->submit();
		$this->setErrors();
	}
}