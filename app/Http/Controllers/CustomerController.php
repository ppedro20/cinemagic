<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Customer;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\CustomerFormRequest;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CustomerController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        //$this->authorizeResource(User::class, 'C');
    }

    public function index(Request $request): View{

        $customersQuery = User::where('type', 'C')
            ->orderBy('name');
        $filterByName = $request->query('name');
        if ($filterByName) {
            $customersQuery->where('name', 'like', "%$filterByName%");
        }
        $customers = $customersQuery
            ->paginate(20)
            ->withQueryString();

        return view(
            'customers.index',
            compact('customers', 'filterByName')
        );
    }

    public function show(User $customer): View
    {
        return view('customers.show',compact('customer'));
    }

    public function edit(User $customer): View
    {
        return view('customers.edit',compact('customer'));
    }

    public function update(CustomerFormRequest $request, Customer $customer){
        $validatedData = $request->validated();
        $user = $customer->user;
        $user->name = $validatedData['name'];
        $user->email = $validatedData['email'];
        $user->save();

        $customer->nif = $validatedData['nif'];
        $customer->payment_type = $validatedData['payment_type'];
        $customer->payment_ref = $validatedData['payment_ref'];
        $customer->save();

        if ($request->hasFile('photo_file')) {
            if (
                $user->photo_filename &&
                Storage::fileExists('public/photos/' . $user->photo_filename)
            ) {
                Storage::delete('public/photos/' . $user->photo_filename);
            }
            $path = $request->photo_file->store('public/photos');
            $user->photo_filename = basename($path);
            $user->save();
        }


        $htmlMessage = "Customer <u>{$user->name}</u> has been updated successfully!";
        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', $htmlMessage);
    }

    public function destroy(Customer $customer): RedirectResponse
    {
        $user = $customer->user;
        $customer->delete();
        $user->delete();
        return redirect()->route('customers.index')
            ->with('alert-type', 'success')
            ->with('alert-msg', "Customer $user->name has been deleted successfully!");
    }
    public function block(User $customer): RedirectResponse
    {
        $customer->blocked = true;
        $customer->save();
        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', "Customer $customer->name has been blocked successfully!");
    }

    public function unblock(User $customer): RedirectResponse
    {
        $customer->blocked = false;
        $customer->save();
        return redirect()->back()
            ->with('alert-type', 'success')
            ->with('alert-msg', "Customer $customer->name has been unblocked successfully!");
    }

    public function destroyPhoto(Customer $customer): RedirectResponse
    {
        $user = $customer->user;
        if ($user->photo_filename) {
            if (Storage::fileExists('public/photos/' . $user->photo_filename)) {
                Storage::delete('public/photos/' . $user->photo_filename);
            }
            $user->photo_filename = null;
            $user->save();
            return redirect()->back()
                ->with('alert-type', 'success')
                ->with('alert-msg', "Photo of customer {$user->name} has been deleted.");
        }
        return redirect()->back();
    }

}
