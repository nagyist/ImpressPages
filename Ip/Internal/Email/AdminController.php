<?php
/**
 * @package ImpressPages
 *
 */

namespace Ip\Internal\Email;


class AdminController extends \Ip\GridController
{



    protected function config()
    {
        return array(
            'type' => 'table',
            'allowCreate' => false,
            'allowUpdate' => false,
            'allowDelete' => false,
            'orderBy' => '`id` desc',
            'table' => 'email_queue',
            'actions' => array(),
            'fields' => array(
                array(
                    'label' => __('Subject', 'Ip-admin', false),
                    'field' => 'subject'
                ),
                array(
                    'label' => __('Email', 'Ip-admin', false),
                    'field' => 'email',
                    'preview' => __CLASS__ . '::html2text'
                ),
                array(
                    'label' => __('Recipient name', 'Ip-admin', false),
                    'field' => 'toName',
                    'preview' => false
                ),
                array(
                    'label' => __('Recipient email', 'Ip-admin', false),
                    'field' => 'to',
                    'preview' => __CLASS__ . '::to'
                ),
                array(
                    'label' => __('Sender name', 'Ip-admin', false),
                    'field' => 'fromName',
                    'preview' => false
                ),
                array(
                    'label' => __('Sender email', 'Ip-admin', false),
                    'field' => 'from',
                    'preview' => __CLASS__ . '::from'
                ),
                array(
                    'label' => __('Sent at', 'Ip-admin', false),
                    'field' => 'send',
                    'preview' => true
                ),
                array(
                    'label' => __('Attachment', 'Ip-admin', false),
                    'field' => 'fileNames'
                ),
                array(
                    'label' => '',
                    'field' => 'id',
                    'preview' => '<a href="#" class="ipsEmailPreview">' . __('Preview', 'SimpleProduct') . '</a>',
                    'allowUpdate' => false,
                    'allowInsert' => false,
                    'allowSearch' => false
                )
            )
        );
    }

    public function index()
    {
        ipAddJs('assets/email.js');
        ipAddCss('assets/email.css');

        $previewModal = ipView('view/previewModal.php');
        return parent::index() . $previewModal;
    }


    public function preview()
    {
        $id = ipRequest()->getQuery('id');
        if (!$id) {
            throw new \Ip\Exception('Email not found');
        }
        $email = Db::getEmail($id);
        $viewData = array(
            'email' => $email
        );
        $content = ipView('view/preview.php', $viewData);
        $response = new \Ip\Response($content);
        return $response;
    }




    public static function html2text($value, $recordData)
    {
        $html2text = new \Ip\Internal\Text\Html2Text('<html><body>' . $value . '</body></html>', false);
        $text = esc($html2text->get_text());
        $text = str_replace("\n", '<br/>', $text);
        return $text;
    }

    public static function to($value, $recordData)
    {
        return esc($recordData['toName'] . ' ' . $value);
    }

    public static function from($value, $recordData)
    {
        return esc($recordData['fromName'] . ' ' . $value);
    }


}
