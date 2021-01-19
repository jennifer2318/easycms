<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use \Illuminate\Support\Facades\Request as HttpRequest;
use Illuminate\Support\Facades\Validator;

class LoginController extends Controller
{

    public $errors = [];

    public function postAction() {
        $params = HttpRequest::all();

        switch ($params['act']) {
            case 'auth' : return $this->loginAction($params); break;
            case 'getUserId' : return $this->getUserId($params);
            default: return $this->execResponse(Validator::make($params, []), []);
        }
    }

    public function getUserId($params) {

        $data = [];

        if (Auth::check() && Auth::user()) {
            $data['userId'] = Auth::user()->id;
        }

        $data['userId'] = 1;

        return $this->execResponse(Validator::make($params, []), $data);
    }

    public function loginAction($params) {

        $validator = Validator::make($params, [
            'login' => 'required',
            'password' => 'required',
        ]);

        if (!$validator->fails()) {
            $user = User::all()
                ->where('login', '=', $params['login'])
                ->first();

            if ($user) {
                if (Hash::check($params['password'], $user['password'])) {
                    Auth::loginUsingId($user['id'], $params['remember'] ?? false);
                }else {
                    $validator->errors()->add('login', Lang::get('validation.failed'));
                }
            }else {
                $validator->errors()->add('login', Lang::get('validation.failed'));
            }
        }

        foreach ($validator->errors()->all() as $error) {
            $this->errors[] = $error;
        }

        return $this->execResponse($validator, [
            'isAuth' => Auth::check(),
        ]);
    }

    public function execResponse($validator, $data) {
        return response()->json([
            'errors' => $validator->errors()->jsonSerialize(),
            'hasError' => $validator->errors()->count() > 0 ? true : false,
            'data' => $data,
        ])->sendHeaders();
    }

    public function indexAction() {
        if (!Auth::check()) {
            return view('admin.auth');
        }else {
            if (Auth::user() && Auth::user()->isAdminAccess()) {
                return redirect(RouteServiceProvider::ADMIN_PAGE);
            }
            return redirect(RouteServiceProvider::HOME);
        }
    }
}
