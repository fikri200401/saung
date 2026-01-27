<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\Setting;
use App\Models\Menu;
use App\Models\Saung;
use Illuminate\Http\Request;

class LandingController extends Controller
{
    public function index()
    {
        // Saung Nyonyah landing page
        return view('landing.saung');
    }

    /**
     * Show active vouchers
     */
    public function vouchers()
    {
        $activeVouchers = Voucher::active()
            ->forLanding()
            ->get();

        return view('landing.vouchers', compact('activeVouchers'));
    }
}
