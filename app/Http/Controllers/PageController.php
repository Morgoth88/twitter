<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PageController extends Controller
{

    /**
     * HomeController constructor.
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function indexPage()
    {
        return view('home');
    }


    /**
     * return user blade template
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function UserPage()
    {
        return view('user');
    }


    /**
     * return blade template with account update form
     * with current user data
     *
     * @param Request $request
     * @return $this
     */
    public function AccountUpdatePage(Request $request)
    {
        return view('auth.accountUpdate')->with('user', $request->user());
    }
}