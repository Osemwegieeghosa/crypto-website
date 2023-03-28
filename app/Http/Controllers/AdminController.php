<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Admin\StoreTransactionRequest;
use App\Http\Requests\Admin\StoreVendorRequest;
use App\Http\Requests\Admin\CreateUserRequest;
use App\Http\Requests\Admin\VendorActionRequest;
use App\Models\Transaction;
use App\Models\Vendor;
use App\Models\User;
use App\Models\Subscription;
use App\Services\GeneralService;

class AdminController extends Controller
{
    protected $generalService;

    public function __construct(GeneralService $generalService)
    {
        $this->generalService = $generalService;
    }

    public function dashboard(){
        return view('admin.dashboard', [
            'title' => 'Admin Dashboard',
            'vendors' => Vendor::all()->count(),
            'users' => User::all()->count(),
            'transactions' => Transaction::all()->count(),
        ]);
    }

    /*======================================================================================================================*/
    
    public function allUsers(){
        return view('admin.users')->with([
            'users' => User::paginate(700)
        ]);
    }

    public function updateUserAccountBalance(Request $request, User $user){
        $request->validate(['amount' => 'numeric|required']);
        $user->update(['available_balance' => $request->get('amount')]);
        return redirect()->route('admin.users')->with('success', 'Account balance of ' . $user->name . ' updated successfully');
    }

    
    /*======================================================================================================================*/

    public function transactions(){
        return view('admin.transactions')->with([
            'transactions' => Transaction::paginate(20)
        ]);
    }

    public function createTransaction(StoreTransactionRequest $request){
        $request['tx_id'] = $this->generalService->generateTxid();
        $transaction = Transaction::create($request->all());
        return redirect()->route('admin.transactions')->with('success', 'Transaction created successfully');
    }

    public function updateTransaction(StoreTransactionRequest $request, Transaction $transaction){
        $transaction->update($request->all());
        return redirect()->route('admin.transactions')->with('success', 'Transaction updated successfully');
    }

    public function deleteTransaction(Transaction $transaction){
        $transaction->delete();
        return redirect()->route('admin.transactions')->with('success', 'Transaction has been moved to trash successfully.');
    }

    
    /*======================================================================================================================*/

    public function vendors(){
        return view('admin.vendors')->with([
            'vendors' => Vendor::all()
        ]);
    }  
    
    public function addVendor(StoreVendorRequest $request, CreateUserRequest $userData){
        $userData['uid'] = $this->generalService->generateUserUid();
        $user = User::create($userData->all());
        $request['status'] = 'active';
        $transaction = $user->vendor()->create($request->all());
        return redirect()->route('admin.vendors')->with('success', 'Vendor added successfully');
    }

    public function updateVendor(StoreVendorRequest $request, Vendor $vendor){
        $vendor->update($request->all());
        return redirect()->route('admin.vendors')->with('success', 'Vendor updated successfully');
    }

    public function deleteVendor(VendorActionRequest $request){
        $vendor = Vendor::findOrFail($request->get('vendor_id'));
        $vendor->delete();
        return redirect()->route('admin.vendors')->with('success', 'Vendor has been moved to trash successfully.');
    }

    public function toggleVendorStatus(VendorActionRequest $request){
        $request->validate(['status' => 'required|string']);
        $vendor = Vendor::findOrFail($request->get('vendor_id'));
        $vendor->update(['status' => $request->get('status')]);
        return redirect()->route('admin.vendors')->with('success', 'Vendor\'s status updated successfully.');
    }
    
    /*======================================================================================================================*/

    public function subscriptions(){
        return view('admin.subscriptions')->with([
            'subscribers' => Subscription::all()
        ]);
    }

    public function subscriptionRequests(){
        return view('admin.subscription-requests')->with([
            // 'subscription_requests' => Subscription::where('status', 'requested')->get()
            'subscription_requests' => Subscription::all()
        ]);
    }

    public function activateSubscription(Subscription $subscription){
        $subscription->update(['status' => 'active']);
        return redirect()->route('admin.subscriptions.index')->with('success', 'Vendor updated successfully');
    }

    public function disableSubscription(Subscription $subscription){
        $subscription->update(['status' => 'disable']);
        return redirect()->route('admin.subscriptions.index')->with('success', 'Vendor has been moved to trash successfully.');
    }
    public function destroy($id)
    {
        $user = User::find($id);
        $user->delete();

        return redirect()->route('admin.users')->with('success', 'User deleted successfully');
    }

// app/Http/Controllers/AdminController.php
    public function users()
    {
        $users = user::all();
        return view('admin.kyc', ['users' => $users]);
    }

    public function verify(User $user)
    {
        $user->update(['is_kyc_verified' => true]);
        return redirect('/admin/kyc')->with('success', 'KYC verification successful.');
    }
    public function kyc()
    {
        $user = User::all();
        return view('admin.kyc', compact('users'));
    }

    public function verifyKYC($id)
    {
        $user = User::findOrFail($id);
        $user->status = 'verified';
        $user->save();
        return redirect()->back()->with('success', __('KYC Verified Successfully.'));
    }

    

    /*======================================================================================================================*/
}
