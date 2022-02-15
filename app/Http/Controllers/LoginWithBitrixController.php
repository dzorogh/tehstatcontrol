<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginWithBitrixController extends Controller
{

    // id=5393&
    // email=s.indenbom%40globaldrive.ru&
    // name=Сергей&
    // last_name=Инденбом&
    // phone=%2B79037349757&
    // photo=https%3A%2F%2Fcrm.globaldrive.ru%2Fupload%2Fmain%2Fe3f%2Fxg216imd19dtxx7s146mm5a5hj28fxwb%2FIMG_2664+2.png&
    // district=Другой&
    // department=Программисты&
    // position=Программист+Москва&
    // hash=d4df5026355c259e88806b466b6869b47fbe5c1e


    public function __invoke(Request $request)
    {
        // TODO: WARNING: this type of validation has security issues

        $request->validate([
            'hash' => 'required',
            'email' => 'required|email',
        ]);

        if ($request->input('hash') == sha1($request->input('email') . env('BITRIX_AUTH_SALT'))) {
            // TODO: authenticate as user
            // Save email in metrika counter

            $user = User::whereEmail(env('TEST_USER_EMAIL'))->first();

            Auth::login($user);
            $request->session()->regenerate();

            return redirect('/?email=' . $request->input('email'));

//            $value = $request->session()->all();
//
//            return response($value);
//
//            return response($request->input('email'));
        } else {
            return 'Wrong credentials';
        }
    }
}
