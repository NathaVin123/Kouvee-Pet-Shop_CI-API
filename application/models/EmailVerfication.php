<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class EmailVerification extends CI_Model
{
    private $table = 'users';

    public function send_mail($user, $id)
    {
        $config['mailtype'] = 'html';
        $config['charset'] = 'utf-8';
        $config['protocol'] = 'smtp';
        $config['smtp_host'] = 'ssl://smtp.gmail.com';
        $config['smtp_user'] = 'vincentnathaniel4@gmail.com';
        $config['smtp_pass'] = '@gamespb';
        $config['smtp_port'] = 465;
        $config['newline'] = "\r\n";
        $config['clrf'] = "\r\n";

        $verif_link = site_url("users/$id/$user->verif_code");

        $message = '
        <h2>Thank you for join with us!</h2>
        <p>Your account is registered now, you need verify your account for login to Lyric Libs</p>
        <br></br>
        <p>---------------------------------------</p>
        <p>Email: '.$user->email.'</p>
        <p>Name: '.$user->name.'</p>
        <p>---------------------------------------</p>
        <br></br>
        <h3>This is your verification link:</h3>
        <form method="post" action="'.$verif_link.'">
        <button type="submit">Activate My Account<button>';

        $this->load->library('email', $config);

        $this->email->from('LyricLibs', 'LYRICLIBS');
        $this->email->to('vincentnathaniel4@gmail.com');
        $this->email->subject('VERIFICATION EMAIL - LYRICLIBS');
        $this->email->message($message);

        if($this->email->send()) {
            return ['msg'=>'Success, email verification send to your email','error'=>false];
        }
        else {
            return ['msg'=>'Fail','error'=>true];
        }
    }
}
?>