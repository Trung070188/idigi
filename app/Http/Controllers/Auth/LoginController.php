<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\AuthenticationLog;
use App\Models\School;
use App\Models\User;
use App\Support\CallApi;
use Carbon\Carbon;
use Faker\Extension\Helper;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\UnauthorizedException;
use Illuminate\Validation\ValidationException;


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

        $usernameType = $this->findUsername();

        $user = User::where($usernameType, $request['login'])->first();


        if($user){
            if($user->state==0)
            {
                throw ValidationException::withMessages([
                    $this->username() => ["Your account has locked. Please contact the administrator to unlock your account.
                    "],
                ]);
            }
            if($user->roles){
                foreach ($user->roles as $role){
                    if($role->role_name == "Teacher"){

                            $school = School::where('id', $user->school_id)->first();
                            if($school!=null)
                            {
                                if($school->license_to==null)
                                {
                                    throw ValidationException::withMessages([
                                        $this->username() => ["Your account has expired. Please contact the administrator to renew your account."],
                                    ]);
                                }
                                if(@$school->license_to < Carbon::now() && @$school->license_to!=null){
                                    throw ValidationException::withMessages([
                                        $this->username() => ["Your account has expired. Please contact the administrator to renew your account."],
                                    ]);

                                }
                            }
                            if($school==null)
                            {
                                throw ValidationException::withMessages([
                                    $this->username() => ["Your account has expired. Please contact the administrator to renew your account."],
                                ]);
                            }




                    }
                }
            }

        }

        if (config('app.env') === 'production') {
            throw new UnauthorizedException("Password sign in is not available");
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

    protected function sendLoginResponse(Request $request)
    {
        #$request->session()->regenerate();
        $user=(Auth::user());
        $authenticationLog= new AuthenticationLog();
        $authenticationLog->user_id=$user->id;
        $authenticationLog->user_agent=$request->userAgent();
        $authenticationLog->ip_address=$this->getClientIp();
        $authenticationLog->login_at=Carbon::now();
        $authenticationLog->save();
        $this->clearLoginAttempts($request);

        if ($response = $this->authenticated($request, $this->guard()->user())) {

            return $response;
        }

        return $request->wantsJson()
            ? new JsonResponse([], 204)
            : redirect()->intended($this->redirectPath());
    }
    private function getClientIp()
    {
        static $ip;

        if (isset ($ip)) {
            return $ip;
        }

        if (!empty ($_SERVER['HTTP_CLIENT_IP']) ) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } else if (!empty ($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else if (!empty ($_SERVER['HTTP_X_FORWARDED'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED'];
        } else if ( !empty ($_SERVER['HTTP_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_FORWARDED_FOR'];
        } else if ( !empty ($_SERVER['HTTP_FORWARDED']) ) {
            $ip = $_SERVER['HTTP_FORWARDED'];
        } else if( !empty ($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = 'UNKNOWN';
        }


        return $ip;
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
