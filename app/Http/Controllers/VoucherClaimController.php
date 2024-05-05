<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use App\Models\VoucherClaim;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VoucherClaimController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(Request $request)
    {
        $validator = Validator::make(request()->all(), [
            'voucher_id' => 'required',
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors()->toArray(); 
            $message = reset($errors)[0];
            return response()->json(["message" => $message], 400);
        }

        $existVoucher = Voucher::where([['id', $request->voucher_id],['status', true]])->first();
        
        if (!$existVoucher) {
            return response()->json(['message' => 'Vouchers are not available'], 404);
        }

        $user = auth()->user();

        $voucherClaim = VoucherClaim::create([
            'id_voucher' => $request->voucher_id,
            'id_user' => $user->id,
            'tanggal_claim' => now(),
        ]);

        if ($voucherClaim) {
            $response['message'] = "Voucher has been successfully claimed";
            return response()->json($response, 201);
        } else {
            $response['message'] = "Voucher failed to claim";
            return response()->json($response, 500);
        }
    }

    public function index()
    {
        $user = auth()->user();

        $claimedVoucherIds = VoucherClaim::where('id_user', $user->id)
        ->pluck('id_voucher')
        ->toArray();

        $claimedVouchers = Voucher::whereIn('id', $claimedVoucherIds)
        ->where('status', true)
        ->get();

        $totalClaimedVoucher = $claimedVouchers->count();

        $listCategory = $claimedVouchers
            ->groupBy('kategori')
            ->map(function ($category) {
                return [
                    'kategori' => $category->first()->kategori,
                    'total_number' => $category->count(),
                ];
            })
            ->values()
            ->all();


        return response()->json([
            'data' => [...$claimedVouchers],
            'total_claimed_voucher' => $totalClaimedVoucher,
            'list_category' => $listCategory
        ]);
    }
}
