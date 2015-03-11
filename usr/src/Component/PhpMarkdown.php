<?php
/**
 * @file      AblePolecat-Mod-PhpMarkdown/usr/src/Component/PhpMarkdown.php
 * @brief     Component uses PHP Markdown to transform plain text with markdown to XHTML.
 * @author    Karl Kuhrman
 * @copyright Copyright © 2015 Able Distributors Inc. All rights reserved.
 */

require_once(implode(DIRECTORY_SEPARATOR, array(ABLEPOLECAT_MOD_PHPMARKDOWN_SRC_PATH, 'Data', 'Primitive', 'Scalar', 'String', 'Markdown.php')));
require_once(implode(DIRECTORY_SEPARATOR, array(ABLE_POLECAT_CORE, 'Component.php')));

interface AblePolecat_Component_PhpMarkdownInterface extends AblePolecat_ComponentInterface {
  
}

class AblePolecat_Component_PhpMarkdown 
  extends AblePolecat_ComponentAbstract 
  implements AblePolecat_Component_PhpMarkdownInterface {
  
  const UUID = 'b720e664-9b45-11e4-ad44-0050569e00a2';
  const NAME = 'AblePolecat_Component_PhpMarkdown';
  
  /**
   * @var Array String substitutions.
   */
  private $entityBodyStringSubstitutes;
  
  /********************************************************************************
   * Implementation of AblePolecat_AccessControl_Article_StaticInterface.
   ********************************************************************************/
  
  /**
   * Return unique, system-wide identifier.
   *
   * @return UUID.
   */
  public static function getId() {
    return self::UUID;
  }
  
  /**
   * Return Common name.
   *
   * @return string Common name.
   */
  public static function getName() {
    return self::NAME;
  }
  
  /********************************************************************************
   * Implementation of AblePolecat_Data_PrimitiveInterface.
   ********************************************************************************/
  
  /**
   * @param DOMDocument $Document.
   *
   * @return DOMElement Encapsulated data expressed as DOM node.
   */
  public function getDomNode(DOMDocument $Document = NULL) {
    //
    // Create a temporary DOM document from template.
    //
    $templateElement = AblePolecat_Dom::appendChildToParent($this->getTemplateElement(), $Document);
    
    //
    // Extract 'repeatable' element(s).
    //
    $regularPhpMarkdownTemplateStr = AblePolecat_Dom::removeRepeatableElementTemplate($Document, 'AblePolecat_Markdown_Outer_Wrapper');
    $AblePolecat_List_PhpMarkdown_Element = AblePolecat_Dom::getElementById($Document, 'AblePolecat_Markdown_Outer_Wrapper');
    
    //
    // Get raw resource (data).
    //
    $Resource = $this->getResource();
    
    //
    // @todo: complete substitution.
    //    
    $substituteMarkers = array_keys($this->entityBodyStringSubstitutes);
    $transformedElementStr = str_replace($substituteMarkers, $this->entityBodyStringSubstitutes, $regularPhpMarkdownTemplateStr);
    $transformedElement = @AblePolecat_Dom::getDocumentElementFromString($transformedElementStr);
    $transformedElement = AblePolecat_Dom::appendChildToParent(
      $transformedElement, 
      $Document,
      $AblePolecat_List_PhpMarkdown_Element
    );
    
    return $templateElement;
  }
  
  /********************************************************************************
   * Implementation of AblePolecat_ComponentInterface.
   ********************************************************************************/
  
  /**
   * Create an instance of component initialized with given resource data.
   *
   * @param AblePolecat_ResourceInterface $Resource.
   *
   * @return AblePolecat_ComponentInterface.
   */
  public static function create(AblePolecat_ResourceInterface $Resource) {
    
    $Component = new AblePolecat_Component_PhpMarkdown();
    $Component->setResource($Resource);
    $args = func_get_args();
    if (isset($args[1]) && is_array($args[1])) {
      $Component->setEntityBodyStringSubstitutes($args[1]);
    }
    return $Component;
  }
  
  /********************************************************************************
   * Helper functions.
   ********************************************************************************/
  /**
   * @return Array [substitute marker => entity body substitute string value].
   */
  public function getEntityBodyStringSubstitutes() {
    return $this->entityBodyStringSubstitutes;
  }
  
  /**
   * @param Array $entityBodyStringSubstitutes [substitute marker => entity body substitute string value].
   */
  public function setEntityBodyStringSubstitutes($entityBodyStringSubstitutes) {
    if (isset($entityBodyStringSubstitutes) && is_array($entityBodyStringSubstitutes)) {
      foreach($entityBodyStringSubstitutes as $marker => $text) {
        $marker = str_replace(array('%7B', '%7D'), array('{', '}'), $marker);
        $transformText = AblePolecat_Data_Primitive_Scalar_String_Markdown::typeCast($text);
        $this->entityBodyStringSubstitutes[$marker] = $transformText;
      }
    }
    else {
      throw new AblePolecat_Exception(sprintf("%s requires first parameter be array. %s passed.",
        __METHOD__,
        AblePolecat_Data::getDataTypeName($Resource)
      ));
    }
  }
  
  /**
   * @return Data expressed as a string.
   */
  public function __toString() {
    //
    // @todo: output element as text
    //
    return '';
  }
  
  /**
   * Extends __construct().
   */
  protected function initialize() {
    $this->setTagName('div');
    $this->getTemplateElement();
    $this->entityBodyStringSubstitutes = array();
  }
}