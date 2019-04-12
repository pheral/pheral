<?php

namespace App\Models\Fitness;

use App\DBTables\Fitness\Users;
use App\Models\Abstracts\Model;
use Pheral\Essential\Storage\Session;

class Auth extends Model
{
    protected $user;

    public function findUser(string $login, string $password)
    {
        $loginHash = sha1('pheral:' . md5($password));
        return Users::query()
            ->where('email', '=', $login)
            ->where('pass', '=', $loginHash)
            ->select()
            ->row();
    }

    public function getUser()
    {
        if (!$this->user) {
            $this->user = Users::query()
                ->with([
                    'gender',
                    'level',
                    'data' => 'option'
                ])
                ->where('id', '=', Session::instance()->get('fuid'))
                ->select()
                ->row();
        }
        return $this->user;
    }
}
