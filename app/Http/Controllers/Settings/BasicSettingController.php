<?php

namespace App\Http\Controllers\Settings;

use Config;
use App\Models\BasicSetting;
use Illuminate\Http\Request;
use App\Helpers\CustomHelper;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Redirect;

class BasicSettingController extends Controller
{
    //

    public function basicContent()
    {
        $data['page_title'] = "Basic Content";
        $data['basic'] = BasicSetting::first();
        return view('backend.settings.basic-content', $data);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function updateBasicContent(Request $request)
    {
        $data = $request->validate([
            'title'    => 'required|max:191',
            'timezone' => 'required',
            'currency' => 'required|max:50',
            'symbol'   => 'required|max:30',
            'phone'    => 'required|max:50',
            'email'    => 'required|email|max:50',
            'address'  => 'required|max:100',
        ]);

        $basic = BasicSetting::first();
        $data['email_verification'] = $request->input("email_verification") == 'on' ? true : false;
        $basic->update($data);

        Config::set('system.basic', [
            'currency' => $basic->currency,
            'symbol'   => $basic->symbol,
        ]);

        Artisan::call('config:cache');

        CustomHelper::changeEnv([
            'APP_NAME' => $request->input('title'),
        ]);

        return redirect()->back()->withToastSuccess('Basic Settings Updated.');
    }

    public function logoFavicon()
    {
        $data['page_title'] = 'Manage Logo & Favicon';
        return view('backend.settings.logo-favicon', $data);
    }

    /**
     * @param Request $request
     */
    public function updateLogoFavicon(Request $request)
    {
        $request->validate([
            'logo'        => 'nullable|mimes:png',
            'footer-logo' => 'nullable|mimes:png',
            'favicon'     => 'nullable|mimes:png',
        ]);

        if ($request->hasFile('logo')) {
            $image = $request->file('logo');
            $filename = 'logo' . '.' . $image->getClientOriginalExtension();
            $location = public_path('img/' . $filename);
            File::delete(public_path('img/logo.png'));
            Image::make($image)->save($location);
        }

        if ($request->hasFile('footer-logo')) {
            $image = $request->file('footer-logo');
            $filename = 'footer-logo' . '.' . $image->getClientOriginalExtension();
            $location = public_path('img/' . $filename);
            File::delete(public_path('img/footer-logo.png'));
            Image::make($image)->save($location);
        }
        if ($request->hasFile('favicon')) {
            $image = $request->file('favicon');
            $filename = 'favicon' . '.' . $image->getClientOriginalExtension();
            $location = public_path('img/' . $filename);
            File::delete(public_path('img/favicon.png'));
            Image::make($image)->resize(50, 50)->save($location);
        }

        return Redirect::back()->withToastSuccess('Logo & Favicon Update Successfully.');
    }
}
