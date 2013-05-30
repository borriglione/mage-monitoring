<?php
/**
 * Rom Monitoring
 *
 * @category  Monitoring
 * @package   Rom_Monitoring
 * @author    André Herrn <info@andre-herrn.de>
 * @license   http://opensource.org/licenses/osl-3.0.php  Open Software License (OSL 3.0)
 */

require_once 'abstract.php';

/**
 * Rom Monitoring Shell Script
 *
 * @category    Monitoring
 * @package     Rom_Monitoring
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Shell_Monitoring extends Mage_Shell_Abstract
{
    /**
     * Run script
     */
    public function run()
    {
        echo "Start Rom Monitoring Test\r\n";


        echo "End Rom Monitoring Test\r\n";
    }

    /**
     * Retrieve Usage Help Message
     */
    public function usageHelp()
    {
        return <<<USAGE
Usage:  php -f indexer.php -- [options]

  --status <indexer>            Show Indexer(s) Status
  --mode <indexer>              Show Indexer(s) Index Mode
  --mode-realtime <indexer>     Set index mode type "Update on Save"
  --mode-manual <indexer>       Set index mode type "Manual Update"
  --reindex <indexer>           Reindex Data
  info                          Show allowed indexers
  reindexall                    Reindex Data by all indexers
  help                          This help

  <indexer>     Comma separated indexer codes or value "all" for all indexers

USAGE;
    }
}

/**
 * Rom Monitoring Core Exception
 *
 * @category    Monitoring
 * @package     Rom_Monitoring
 * @author      André Herrn <info@andre-herrn.de>
 */
class Rom_Shell_Core_Exception
{
    const CORE_EXCEPTION_INI = "rom_monitoring/ini/exception_mail.ini";

    /**
     * Send an email with a report about an exception
     * 
     * @param  Exception $exception
     * @return void
     */
    public function sendMailForException($exception)
    {
        try {
            $mailBody = $this->buildExceptionMailBody($exception);
            $mailInformation = parse_ini_file(self::CORE_EXCEPTION_INI);
            $mailHeader = "From: ". $mailInformation["from_name"] . " <" . $mailInformation["from_email"] . ">\r\n";

            if (false === is_array($mailInformation["recipients"])) {
                throw new Exception("No recipient given for the Rom Monitoring Core Exception");
            }

            //Send exception email to every recipient
            foreach ($mailInformation["recipients"] as $recipientEmail) {
                mail($recipientEmail, $mailInformation["subject"], $mailBody, $mailHeader);
                echo "Exception mail sent to {$recipientEmail}\r\n";
            }
        } catch (Exception $e) {
            //Unable to send my own exception, this is the end my friend
            throw $e;
        }
    }

    /**
     * Build body for exception mail
     * @param  Exception $exception
     * @return string
     */
    protected function buildExceptionMailBody($exception)
    {
        $reportMessage = sprintf(
            "An error happened during the Startup of the Magento Monitoring Module.\n".
            "The Monitoring-Testsuite could not been started.\n\n".
            "Error Message:          %s\n".
            "Code:                   %s\n".
            "File:                   %s\n".
            "Line:                   %s\n".
            "Trace:                  %s\n\n".
            "Your faithful employee,\n".
            "Rom Monitoring",
            $exception->getMessage(),
            $exception->getCode(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString()
        );
        return $reportMessage;
    }
}

//Run Monitoring Script
try {
    $shell = new Rom_Shell_Monitoring();
    $shell->run();
} catch (Exception $e) {
    /*
     * It seems to be a problem to initialize the Rom_Shell_Monitoring class
     * or Magento itself
     */
    $romShellCoreException = new Rom_Shell_Core_Exception();
    $romShellCoreException->sendMailForException($e);
}
