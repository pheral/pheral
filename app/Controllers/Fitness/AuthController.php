<?php

namespace App\Controllers\Fitness;

use App\Controllers\Abstracts\FitnessController;
use Pheral\Essential\Network\Frame;
use Pheral\Essential\Storage\Session;

class AuthController extends FitnessController
{
    public function __construct()
    {
        parent::__construct();
        $this->path = 'layouts.fitness.auth';
    }

    public function index()
    {
        return $this->render([
            'content' => view('templates.fitness.auth.login', [
                'error' => Session::instance()->getFlush('error')
            ])
        ]);
    }

    public function login()
    {
        $frame = Frame::instance();
        if (!$login = $frame->request()->get('login')) {
            $frame->session()->setFlush('error', 'не указан логин');
            return redirect()->back();
        }
        if (!$password = $frame->request()->get('password')) {
            $frame->session()->setFlush('error', 'не указан пароль');
            return redirect()->back();
        }
        if (!$user = $this->auth->findUser($login, $password)) {
            $frame->session()->setFlush('error', 'некорректные данные');
            return redirect()->back();
        }
        $frame->session()->set('fuid', $user->id);
        $url = url()->path('/fitness');
        return redirect($url);
    }

    public function logout()
    {
        Session::instance()->clear();
        return redirect('/fitness/auth');
    }
}
