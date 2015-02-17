<?php
/**
 * @file      AblePolecat-Mod-PhpMarkdown/usr/src/Data/Primitive/Scalar/String/Markdown.php
 * @brief     Encapsulates plain text with markdown syntax transformed to XHTML.
 * @author    Karl Kuhrman
 * @copyright Copyright © 2015 Able Distributors Inc. All rights reserved.
 */

if (!defined('PHP_MARKDOWN_LIB_PATH')) {
  $PHP_MARKDOWN_LIB_PATH = implode(DIRECTORY_SEPARATOR, array(AblePolecat_Server_Paths::getFullPath('php-markdown'), 'Michelf'));
  define('PHP_MARKDOWN_LIB_PATH', $PHP_MARKDOWN_LIB_PATH);
}
require_once(implode(DIRECTORY_SEPARATOR, array(PHP_MARKDOWN_LIB_PATH, 'Markdown.inc.php')));
use \Michelf\Markdown;

require_once(implode(DIRECTORY_SEPARATOR, array(ABLE_POLECAT_CORE, 'Data', 'Primitive', 'Scalar', 'String.php')));

class AblePolecat_Data_Primitive_Scalar_String_Markdown extends AblePolecat_Data_Primitive_Scalar_String {
  
  /********************************************************************************
   * Implementation of AblePolecat_Data_PrimitiveInterface.
   ********************************************************************************/
  
  /**
   * Casts the given parameter into an instance of data class.
   *
   * @param mixed $data
   *
   * @return Concrete instance of AblePolecat_Data_PrimitiveInterface
   * @throw AblePolecat_Data_Exception if type cast is invalid.
   */
  public static function typeCast($data) {
    $strData = parent::typeCast($data);
    $transformText = Markdown::defaultTransform($strData);
    return $transformText;
  }
}