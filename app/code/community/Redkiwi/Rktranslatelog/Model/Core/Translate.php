<?php

/**
 * @category    Redkiwi
 * @package     Redkiwi_Rktranslatelog
 * @author      Sander Mangel <mangel@redkiwi.nl>
 */
class Redkiwi_Rktranslatelog_Model_Core_Translate extends Mage_Core_Model_Translate
{

    /**
     * Return translated string from text.
     *
     * @param string $text
     * @param string $code
     * @return string
     */
    protected function _getTranslatedString($text, $code)
    {
		$config_logger = (int)Mage::getStoreConfig('rktranslatelog/general/logger', Mage::app()->getStore()->getId());
		$config_translatecsv = (int)Mage::getStoreConfig('rktranslatelog/general/logger', Mage::app()->getStore()->getId());
		
		$translated = '';
        if (array_key_exists($code, $this->getData())) {
            $translated = $this->_data[$code];
        }
        elseif (array_key_exists($text, $this->getData())) {
            $translated = $this->_data[$text];
        }
        else {
		
			/**
			 * Log string and extra info when not found
			 */
			if (!empty($this->_locale))
			{
				if ($config_translatecsv) // create CSV if enabled
					$this->_createTranslateFile($text, $this->_locale);
				
				if ($config_logger) // create log if enabled
					Mage::log("local: '{$this->_locale}', text: '{$text}', module: '{$code}'", null, 'translate.log', true);
			}
			
            $translated = $text;
        }
        return $translated;
    }
	

    /**
     * Write unfound strings to translate.csv
     *
     * @param string $text
     * @param string $locale
     * @return n/a
     */
	protected function _createTranslateFile($text, $locale)
	{
		$translatecsv = Mage::getBaseDir('var') . DS . 'translate' . DS . $locale . DS . 'translate.csv';
		
		if (!file_exists($translatecsv)) // new translation file
		{
			mkdir(dirname($translatecsv), 0777, true);
			if ($fp = fopen($translatecsv,"wb"))
			{
				fwrite($fp,"\"{$text}\",\"\"\n");
				fclose($fp);
			}
		}
		else // file exists, only write string if not exists
		{
			$execgrep = exec('grep \''.$text.'\' '.$translatecsv);
			if (empty($execgrep))
			{
				if ($fp = fopen($translatecsv,"a"))
				{
					fwrite($fp,"\"{$text}\",\"\"\n");
					fclose($fp);
				}
			}
		}
	}
}
