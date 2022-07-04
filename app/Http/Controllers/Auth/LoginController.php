<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Support\CallApi;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Validation\UnauthorizedException;


class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/xadmin/dashboard/index';

    /**
     * Attempt to log the user into the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return bool
     */
    protected function attemptLogin(Request $request)
    {
        return $this->guard()->attempt(
            $this->credentials($request), $request->filled('remember')
        );
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {

        return view('auth.login');
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function logout(Request $request)
    {
        auth()->logout();
        return redirect()->route('login');
    }

    /**
     * Handle a login request to the application.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\Response|\Illuminate\Http\JsonResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    public function login(Request $request)
    {
        if (config('app.env') === 'production') {
            throw new UnauthorizedException("Password sign in is not available");
        }

        if($request->is_ismart){
            $res = $this->loginSSO($request);

            if($res['status_code'] == 200){
               $body = json_decode($res['body'], true);
                $userInfo = $this->getInfoSSO($body['access_token']);
                $userData = [
                    'username' => $userInfo['name'],
                    'password' => '123456',
                    'full_name' => $userInfo['ismartuser']['fullname'],
                    'email' => $userInfo['ismartuser']['email'],
                    'sso_id' => $userInfo['sub'],
                    'state' => 1,
                ];

                $user = User::updateOrCreate([
                    'sso_id' =>$userInfo['sub']
                ], $userData);

                \Auth::loginUsingId($user->id);

            }else{

                return $this->sendFailedLoginResponse($request);
            }
        }
        $this->validateLogin($request);

        // If the class is using the ThrottlesLogins trait, we can automatically throttle
        // the login attempts for this application. We'll key this by the username and
        // the IP address of the client making these requests into this application.
        if (method_exists($this, 'hasTooManyLoginAttempts') &&
            $this->hasTooManyLoginAttempts($request)) {
            $this->fireLockoutEvent($request);

            return $this->sendLockoutResponse($request);
        }

        if ($this->attemptLogin($request)) {
            return $this->sendLoginResponse($request);
        }

        // If the login attempt was unsuccessful we will increment the number of attempts
        // to login and redirect the user back to the login form. Of course, when this
        // user surpasses their maximum number of attempts they will get locked out.
        $this->incrementLoginAttempts($request);

        return $this->sendFailedLoginResponse($request);
    }

    public function loginSSO(Request $request)
    {
        $data = [
            'client_id' => env('CLIENT_ID'),
            'grant_type' => 'password',
            'username' => $request->login,
            'password' => $request->password,
        ];

        $uri = env('SSO_URL') . "/connect/token";

        $res = CallApi::sendRequest('POST', $uri, $data);

        return $res;
    }

    public function getInfoSSO($accessToken){
        $uri = env('SSO_URL')."/connect/userinfo";
        $data = ['access_token' => $accessToken];

        $res = CallApi::sendRequest('POST',$uri,  $data);
        $body = json_decode($res['body'], true);

        return $body;

    }

    /**
     * Get the login username to be used by the controller.
     *
     * @return string
     */
    public function findUsername()
    {
        $login = request()->input('login');

        $fieldType = filter_var($login, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';

        request()->merge([$fieldType => $login]);

        return $fieldType;
    }

    /**
     * Get username property.
     *
     * @return string
     */
    public function username()
    {
        return $this->findUsername();;
    }
}
