<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class KYCController extends Controller
{
    public function index()
    {
         $countries = include(resource_path('countries.php'));
        return view('user.kyc.index', [
        'countries' => $countries,
    ]);
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        $user->country_issued = $request->country_issued;
        $user->document = $request->document;
        $user->document_number = $request->document_number;
        $user->issue_date = $request->issue_date;
        $user->expiry_date = $request->expiry_date;

        if ($request->hasFile('document_image')) {
            $file = $request->file('document_image');
            $path = $file->store('public/kyc');
            $user->document_image = $path;
        }

        if ($request->hasFile('selfie_image')) {
            $file = $request->file('selfie_image');
            $path = $file->store('public/kyc');
            $user->selfie_image = $path;
        }

        $user->save();

        return redirect()->route('kyc.index')->with('success', 'Your KYC information has been submitted successfully. Please wait for verification.');
    }

    public function adminIndex()
    {
        $users = User::whereNotNull('country_issued')
                    ->whereNotNull('document')
                    ->whereNotNull('document_number')
                    ->whereNotNull('issue_date')
                    ->whereNotNull('expiry_date')
                    ->whereNotNull('document_image')
                    ->whereNotNull('selfie_image')
                    ->orderByDesc('created_at')
                    ->get();

        return view('admin.kyc', compact('users'));
    }

    public function adminVerify($id)
    {
        $user = User::findOrFail($id);
        $user->is_verified = true;
        $user->save();

        return redirect()->route('admin.kyc.index')->with('success', 'User KYC information has been verified successfully.');
    }
}
