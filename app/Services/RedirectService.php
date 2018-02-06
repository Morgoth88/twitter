<?php

namespace App\Services;

class RedirectService
{

    /**
     * redirect to page with flash session message
     *
     * @param $request
     * @param $route
     * @param $flashName
     * @param $flashMessage
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function redirectWithFlash($request, $route, $flashName,
                                      $flashMessage)
    {
        $request->session()->flash($flashName, $flashMessage);
        return redirect(route($route));
    }

}