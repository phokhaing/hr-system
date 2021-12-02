<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Support\Facades\Route;
use Log;
use Mail;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception $exception
     * @return void
     */
    public function report(Exception $e)
    {
        // send only on production environment
        if (env('APP_ENV') == 'production') {
            $this->sendErrorLog($e);
        }
        parent::report($e);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \Exception               $exception
     * @return \Illuminate\Http\JsonResponse
     */
    public function render($request, Exception $exception)
    {
        if ($exception instanceof \Spatie\Permission\Exceptions\UnauthorizedException) {
            return response()->json([
                'responseMessage' => 'You do not have the required authorization.',
                'responseStatus'  => 403,
            ]);
        }

        Log::emergency('Critical', [
            'Request'   => $request,
            'exception' => $exception
        ]);

        return parent::render($request, $exception);
    }

    private function sendErrorLog($e){

        $receivers = [
            ['name'=>'Developer', 'email'=>'skpstsk@gmail.com'],
            ['name'=>'Developer', 'email'=>'ratana.k@sahakrinpheap.com.kh'],
        ];

        //it not error just user expired session
        if(@$e->getMessage() == 'Unauthenticated.')
            return false;

        foreach($receivers as $obj){

            $to_name = @$obj['name'];
            $to_email = @$obj['email'];
            
            $data = [
                'name' => @$to_name,
                'msg' => @$e->getMessage(),
                'line' => @$e->getLine(),
                'action' => @Route::currentRouteAction(),
                'fullUrl' => @$_SERVER['REQUEST_URI'],
                'user' => @\Auth::user()->id.': '.@\Auth::user()->full_name,
                'time' => date('d-M-Y H:i:s A', strtotime(now())),
            ];
    
            Mail::send('emails.error_log', $data, function($message) use ($to_name, $to_email) {
                $message->to($to_email, $to_name)->subject('STSK Group HRMS Error Logs');
                $message->from('info.sahakrinpheap@gmail.com','STSK Group HRMS System');
            });
        }

    }
}
