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
    // Create element containing markdown text.
    //
    AblePolecat_Dom::createRepeatableElementFromTemplate(
      $Document,
      $AblePolecat_List_PhpMarkdown_Element,
      $this->getEntityBodyStringSubstitutes(),
      $regularPhpMarkdownTemplateStr
    );
    
    return $templateElement;
  }
  
  /********************************************************************************
   * Implementation of AblePolecat_ComponentInterface.
   ********************************************************************************/
  
  /**
   * Create an instance of component initialized with given resource data.
   *
   * @param AblePolecat_Registry_Entry_ComponentInterface $ComponentRegistration
   * @param AblePolecat_ResourceInterface $Resource.
   *
   * @return AblePolecat_ComponentInterface.
   */
  public static function create(
    AblePolecat_Registry_Entry_ComponentInterface $ComponentRegistration,
    AblePolecat_ResourceInterface $Resource
  ) {
    
    $Component = new AblePolecat_Component_PhpMarkdown($ComponentRegistration, $Resource);
    $args = func_get_args();
    if (isset($args[2]) && is_array($args[2])) {
      $Component->setEntityBodyStringSubstitutes($args[2]);
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
        // $marker = str_replace(array('%7B', '%7D'), array('{', '}'), $marker);
        //
        // Remove encoded merge field delimiters (merge field syntax converts 'text' into '{!text}').
        //
        $marker = str_replace(array('%7B!', '%7D'), '', $marker);
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
    $this->entityBodyStringSubstitutes = new AblePolecat_Data_Primitive_Array();
  }
}