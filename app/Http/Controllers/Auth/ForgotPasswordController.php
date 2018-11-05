<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use App\Libraries\AfricasTalkingGateway as Bulk;
use App\SmsHandler;
use App\Http\Controllers\SmsController;


class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    public function sendResetLinkEmail(Request $request)
    {

        $this->validate($request, ['username' => 'required|exists:users,username']);

        $user = User::where('username',$request->get('username'))
                    ->whereRaw("unix_timestamp(now()) - unix_timestamp(updated_at) > 300")
                    ->first();

        $token = mt_rand(100000, 999999);

        try{
            $user->sms_code = $token;
            $user->save();
        }catch( \Exception $e){
\Log::info($e->getMessage());
\Log::info("Message already sent to user.");
            return $this->sendResetLinkFailedResponse($request, "We've already sent you a password reset token. Please wait for 3 minutes before retrying.");
        }
     try
     
    {

        $ManageSms = new SmsController;
        $ManageSms->ForgotPasswordResetVerificationCodeSms($user);

     }
     catch (\Exception $e)
     {
      return $this->sendResetLinkFailedResponse($request, $e->getMessage());       
     }

        $response = $this->broker()->createToken($user);

        return redirect('/password/code/?id='.$user->id.'&token='.$response);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        return back()->withErrors(
            ['username' => trans($response)]
        );
    }

    public function codeVerify(Request $request)
    {

        return view('auth.passwords.code');
    }

    public function passwordCodeVerification(Request $request)
    {
        $token = $request->code;
        $userId = $request->id;
        $resetToken = $request->token;

        $check = User::where([
            "id"=>$userId,
            "sms_code"=>$token
        ])->withTrashed()->first();

        if(!is_null($check)){

            return redirect('password/reset/'.$resetToken.'?u='.$check->username);
        }
        return back()->withErrors(['code' => 'Invalid Username Entered']);

    }
}
