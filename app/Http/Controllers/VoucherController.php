<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherClaim;
use Illuminate\Http\Request;

class VoucherController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function index(Request $request) 
    {
        $category = $request->query('category');
        
        $user = auth()->user();
        $claimedVoucherIds = VoucherClaim::where('id_user', $user->id)
        ->pluck('id_voucher')
        ->toArray();

        $unclaimVoucher = Voucher::whereNotIn('id', $claimedVoucherIds)
        ->where('status', true)
        ->get();

        $listVoucherCategory = $unclaimVoucher->map(function ($voucher) {
            return $voucher->kategori;
        })->unique();

        if ($category && $category != null) {
            $unclaimVoucher = $unclaimVoucher->filter(function ($item) use ($category) {
                return $item->kategori == $category;
            });
        }

        return response()->json([
            'data' => [...$unclaimVoucher],
            'list_voucher_category' => [...$listVoucherCategory],
        ]);
    }
}
