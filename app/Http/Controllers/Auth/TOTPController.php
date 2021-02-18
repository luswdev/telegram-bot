<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

use Session;
use Storage;

class TOTPController extends GoogleAuthController
{
    public function main(Request $request, string $code) {
        $config = json_decode(Storage::disk('local')->get('bot.json'));
        $secret = $config->secret;
        $oneCode = $this->getCode($secret);

        if ($this->verifyCode($secret, $code)) {
            $request->session()->put('verify', 'true');
            $request->session()->save();

            $res = array(
                "ok" => true,
                "verify"=> $request->session()->get('verify')
            );
        } else {
            $res = array(
                "ok" => false,
                "verify"=> $request->session()->get('verify')
            );
        }

        return json_encode($res, JSON_PRETTY_PRINT);
    }
}
