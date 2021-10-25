<?php

namespace Infrastructure\Libraries;

use Infrastructure\Exceptions as EfyException;

class Mail
{
	private $template_dir;
	public function __construct() {
        $this->template_dir = \Config('config.mail.template_mail_dir');
	}

	public function createLostPass($form, $options)
    {
        $email      = $options['email'];
        $symbol     = date('dmHis/Y'). '/TB-eBHXH';
        // Gui email
        $subject = 'Thông báo';
        $body = $this->setBody($form, array(
                '__link__'      => $options['link'],
                '__username__'  => $options['username'],
                '__name__'      => $options['name'],
                '__so__'        => $symbol,
                '__c_dia_ban__' => 'Hà Nội',
                '__ngay__'      => date('d'),
                '__thang__'     => date('m'),
                '__nam__'       => date('Y'),
            )
        );
        $body     = $this->headerTemplate($form).$body.$this->footerTemplate();
        $params = array(
            'email'       => $email
            ,'subject'    => $subject
            ,'body'       => $body
            ,'listFile'   => []
            ,'senderName' => 'Base API'
        );
        $this->processMail($params);
    }

    private function headerTemplate($form)
    {
        $form = strtoupper($form);
        $form = str_replace(array('.HTML','.HTM'), '', $form);
        // $html = '<div style="width:795px; height:44px;" align="right"><p style="text-align: right;font-family:\'Times New Roman\', Times, serif;font-size:12px;text-decoration:underline;}">'.str_replace('IHD_','',$form).'/EFY-IHOADON</p></div>';
        $html = '<div style="width:795px; height:44px;" align="right"><p style="text-align: right;font-family:\'Times New Roman\', Times, serif;font-size:12px;text-decoration:underline;}"></p></div>';
        return $html;
    }

    private function footerTemplate()
    {
        $html = '<div style="border-top:2px solid #CCC;float:left;width:795px;margin-top:50px;height:72px;font-family:\'Times New Roman\', Times, serif;"></div>';
        return $html;
    }

	private function setBody($form, $options)
    {
        $templatePath = $this->template_dir . '/'. $form . '.html';
        $content      = file_get_contents($templatePath);
        $tags         = array_keys($options);
        foreach ($tags as $value) {
            $content = str_replace($value, $options[$value], $content);
        }
        return $content;
    }
    public function processMail($arrParams) {
        \Mail::send([], [], function($message) use ($arrParams){
          $message->to($arrParams['email'])
            ->subject($arrParams['subject'])
            ->setBody($arrParams['body'], 'text/html');
        });
    }
}
