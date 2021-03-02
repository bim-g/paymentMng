<?php
namespace Wepesi\App;
use Exception;
class Email extends appException{
    private $subject,$from,$to,$body,$message,$url;
    private $email,$emailVerification;

    function __construct()
    {
        $this->subject="Bienvenu";
        $this->from="From:infos@doctawetu.com";
        $this->body="";
        $this->message="";
        $this->url="https://doctawetu";
        $this->title="Doctawetu";
    }

    function from($email){
        $this->from="from:".$email;
        return $this;
    }

    function send($message){
        $this->header($message);
        return true;
    }
    private function header($message){
        try {

            // $url = "https://www.doctawetu.com/user/activation/?key=" . $emailVerification . "&timeline=" . $this->to;
            $body = "<!DOCTYPE html>
                    <html>
                        <head>
                            <meta charset='UTF-8'>
                            <title>{$this->title}</title>
                            <style>
                                html,body{font-family:Verdana,sans-serif;font-size:15px;line-height:1.5}
                                html{overflow-x:hidden}
                                a{text-decoration:none;padding:5px; }
                                h3{padding:5px;}
                                .header{background:#07366d;padding:5px;border-bottom:solid 1px gray;color:#fff;text-align: center;
                                }
                                .body{padding:10px;}
                                .footer{background:#34373b;padding:5px;border-bottom:solid 1px gray;color:#c5c5c5;text-align: center;}
                                    .btn{background:#0d9ecaee;padding:5px;color:#fff}
                                    
                            </style>
                        </head>
                        <body>
                            <div class=\"header\">
                                <h2>Glomeec</h2>
                            </div>
                            <div class=\"body\">{$message}</div>
                            <div class=\"footer\">
                                <p> clickez <a href='https://doctawetu.com/user/" . $this->email . "/" . $this->emailVerification . "' style='color:red;'>ici</a> si le message n'est pas apropriez</p>
                            </div>
                        </body>
                    </html>";

            $header = "From:" . $this->form;
            $header .= "MIME_Version:1.0\n";
            $header .= "Content-tyoe:text/html;charset=iso-8859\n";
            mail($this->to, $this->subject, $body, $header);
            //return "success creation compte";
        } catch (Exception $ex) {
            return $this->exception($ex);
        }
    }
}