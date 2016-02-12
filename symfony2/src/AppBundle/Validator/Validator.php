<?php
namespace AppBundle\Validator;

use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Email;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;

class Validator{
	
	protected $arrstrErrors;
	protected $boolError;
	protected $objForm;
	protected $objRequest;
	protected $arrmixFormData;
	
	public function hasError() {
		return $this->boolError;
	}
	
	public function getErrors() {
		return $this->arrstrErrors;
	}
	
	public function getForm() {
		return $this->objForm;
	}
	
	public function setForm( $objForm ) {
		$this->objForm = $objForm;
	}
	
	public function setRequest( $objRequest ) {
		$this->objRequest = $objRequest;
	}
	
	public function setDataByRequest( $objRequest ) {
		$this->arrmixFormData = json_decode( $objRequest->getContent(), true );
	}
	
	public function setDataByJson( $strJson ) {
		$this->arrmixFormData = json_decode( $strJson, true );
	}
	
	public function getData( $strField = null ) {
		
		if( isset( $this->arrmixFormData[$strField] ) ) {
			return $this->arrmixFormData[$strField];
		}
		return $this->arrmixFormData;
		
	}
	
	protected function submit() {
		$this->objForm = $this->objForm->getForm();
		$this->objForm->submit( $this->getData() );
	}
	
	protected function setErrors() {
		$this->arrstrErrors = [];
		foreach( $this->objForm->all() as $objChildForm ) {
			if( false == $objChildForm->isValid() ) {
				$this->boolError = true;
				foreach ( $objChildForm->getErrors()  as $objError ) {
					$this->arrstrErrors[$objChildForm->getName()][] = $objError->getMessage();
				}
			}
		}
	}
	
	protected function addEmail( $strFieldName, $intMinLength = 6 ) {
		$arrstrFormConstraints = array(
			new Email(),
			new Length( array( 'min' => $intMinLength ) ),
			new NotBlank()
		);
		$this->add(
			array( $strFieldName, new EmailType(),  $arrstrFormConstraints )
		);
	}
	
	protected function addText( $strFieldName, $intMinLength = 4 ) {
		$arrstrFormConstraints = array(
			new Length( array( 'min' => 4 ) ),
			new NotBlank()
		);
		$this->add(
			array( $strFieldName, new TextType(),  $arrstrFormConstraints )
		);
	}
	
	protected function add( $arrmixConfig ) {
		list( $strField, $objFieldType, $arrstrConstraints ) = $arrmixConfig;
		
		$this->objForm->add( $strField, $objFieldType, array(
			'constraints' => $arrstrConstraints
		) );
	}
	
}