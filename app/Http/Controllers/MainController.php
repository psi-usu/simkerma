<?php

namespace App\Http\Controllers;

use App\Simsdm;
use App\UserAuth;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use View;

class MainController extends Controller {
    // url for asset outside folder laravel
    public $assetUrl;
    // global css
    public $css = [];
    // global js
    public $js = [];
    // body class
    public $bodyClass = "page-session page-sound page-header-fixed page-sidebar-fixed";
    // sidebar left class
    public $sidebarClass = "sidebar-circle";

    public $page_title = 'SIMKERMA USU - Sistem Kerjasama Universitas Sumatera Utara';

    public $v_auths = [];

    private $operator_email = [
        '0' => 'lpm@usu.ac.id'
    ];

    protected $user_info;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->setApp();

        $this->middleware(function ($request, $next)
        {
            $this->setUserInfo();

            return $next($request);
        });
    }

    /**
     * initialize blankon
     */
    public function setApp()
    {

//        $this->assetUrl = config('constant.assetUrl');

        // set global mandatory css
        $this->css = [
            'globals' => [
                'bootstrap/css/bootstrap.min.css'
            ]
        ];

        // theme styles
        $this->css['themes'] = [
            'css/reset.css',
            'css/layout.css',
            'css/components.css',
            'css/plugins.css',
            'css/themes/laravel.theme.css'   => ['id' => ''],
            'css/themes/blue-gray.theme.css' => ['id' => 'theme'],
            'css/custom.css',
        ];

        $this->css['pages'] = [];

        $this->js = [
            'cores' => $this->getCoresJs(),
            'ies'   => $this->getIesJs()
        ];

        $this->js['plugins'] = [];

        $this->js['scripts'] = [
            'js/apps.js',
            'js/pages/blankon.dashboard.js',
            'js/demo.js'
        ];

        // pass variable to view
//        View::share('assetUrl', $this->assetUrl);
        View::share('bodyClass', $this->bodyClass);
        View::share('sidebarClass', $this->sidebarClass);
    }

    /**
     * get js core scripts
     * @return array blankon's core javascript plugins
     */
    private function getCoresJs()
    {
        return [
            'global/plugins/bower_components/jquery/dist/jquery.min.js',
            'global/plugins/bower_components/jquery-cookie/jquery.cookie.js',
            'global/plugins/bower_components/bootstrap/dist/js/bootstrap.min.js',
            'global/plugins/bower_components/typehead.js/dist/handlebars.js',
            'global/plugins/bower_components/typehead.js/dist/typeahead.bundle.min.js',
            'global/plugins/bower_components/jquery-nicescroll/jquery.nicescroll.min.js',
            'global/plugins/bower_components/jquery.sparkline.min/index.js',
            'global/plugins/bower_components/jquery-easing-original/jquery.easing.1.3.min.js',
            'global/plugins/bower_components/ionsound/js/ion.sound.min.js',
            'global/plugins/bower_components/bootbox/bootbox.js',
        ];
    }

    /**
     * get Internet Explorer plugin
     * @return array javascript plugins for IE
     */
    private function getIesJs()
    {
        return [
            'global/plugins/bower_components/html5shiv/dist/html5shiv.min.js',
            'global/plugins/bower_components/respond-minmax/dest/respond.min.js'
        ];
    }

    public function storeDownloadLog($propose_id, $download_type, $file_name_ori, $file_name, $created_by)
    {
        DownloadLog::create([
            'propose_id'    => $propose_id,
            'download_type' => $download_type,
            'file_name_ori' => $file_name_ori,
            'file_name'     => $file_name,
            'created_by'    => $created_by,
        ]);
    }

    public function setUserInfo()
    {
        $this->user_info = [
            'username' => '',
            'full_name' => '',
            'email'     => '',
        ];
        if (Auth::user())
        {
            $simsdm = new Simsdm();
            $user = $simsdm->getEmployee(Auth::user()->username);
            if (isset($user)){
                $this->user_info = [
                    'username'  => Auth::user()->username,
                    'full_name' => $user->full_name,
                    'photo'     => $user->photo
                ];
            }
        }
        View::share('user_info', $this->user_info);
    }

    public function isAdmin($username)
    {
        $check=false;
        $user = UserAuth::where('username',$username)->where('auth_type','SU')->orWhere('auth_type','SAU')->first();

        if(isset($user)){
            $check=true;
        }
        return $check;
    }

    public function isUnit($username)
    {
        $check=false;
        $user = UserAuth::where('username',$username)->where('auth_type','SU')->orWhere('auth_type','AU')->first();

        if(isset($user)){
            $check=true;
        }
        return $check;
    }
}
