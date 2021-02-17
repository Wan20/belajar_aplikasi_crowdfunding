<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class MainController extends Controller
{
    public function route1() {        
        return 'Halaman ini dapat diakses, email anda telah terverifikasi';
    }
    
    public function route2() {
        return 'Halaman ini dapat diakses, email anda telah terverifikasi dan juga anda terdaftar sebagai admin';
    }
}
