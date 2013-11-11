<?php

/**
 * @package ImpressPages
 *
 *
 */

namespace Ip\Module\System;


class Cron {

    function execute($options) {
        $parametersMod = \Ip\ServiceLocator::getParametersMod();

        
        
        if ($options->firstTimeThisDay) {
            if (ipGetOption('Config.keepOldRevision') != 0) {
                \Ip\Revision::removeOldRevisions(ipGetOption('Config.keepOldRevision'));
            }
            $this->checkForUpdates();
        }        
        

    }

    private function checkForUpdates()
    {
        $parametersMod = \Ip\ServiceLocator::getParametersMod();

        $module = new Module();
        $systemInfo = $module->getSystemInfo();
        if ($systemInfo != '') { //send an email

            $md5 = \DbSystem::getSystemVariable('last_system_message_sent');

            if( !$md5 || $md5 != md5($systemInfo) ) { //we have a new message


                $message = '';
                $messages = json_decode($systemInfo);
                if(is_array($messages)) {
                    foreach($messages as $messageKey => $messageVal) {
                        $message .= '<p>'.$messageVal->message.'</p>';
                    }

                    $onlyStatusMessages = true;
                    foreach($messages as $messageKey => $messageVal) {
                        if ($messageVal->type != 'status') {
                            $onlyStatusMessages = false;
                        }
                    }

                    if ($onlyStatusMessages) {
                        return; //TODO replace to something that would not terminate execution of following scripts if they will be there some day
                    }

                } else {
                    return; //TODO replace to something that would not terminate execution of following scripts if they will be there some day
                }

                if (\Ip\Config::getRaw('ERRORS_SEND')) {
                    $queue = new \Ip\Module\Email\Module();
                    $queue->addEmail(
                        ipGetOption('Config.websiteEmail'),
                        ipGetOption('Config.websiteTitle'),
                        \Ip\Config::getRaw('ERRORS_SEND'),
                        '',
                        ipGetOption('Config.websiteTitle'),
                        $message,
                        false,
                        true);
                    $queue->send();
                }

                \DbSystem::setSystemVariable('last_system_message_sent', md5($systemInfo));
            }



        }
    }

}

