<?php
namespace AppBundle\Validator\Security;

use AppBundle\Validator\Validator;


class FacebookValidator extends Validator{

	public function __construct( $objForm, $objRequest = null ) {
		$this->setForm( $objForm );
		if( false == is_null( $objRequest ) ) {
			$this->setDataByRequest( $objRequest );
		}
	}

	public function validate( $arrstrValidateConfig ) {
		
		foreach( $arrstrValidateConfig as $strValidateConfig ) {
			$this->addText( $strValidateConfig, 10 );
		}
		
		$this->submit();
		$this->setErrors();
	}
}