<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class CustomerController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        $customers = Customer::withCount('services')
            ->when($search, function ($query, $search) {
                $query->where('name', 'like', "%{$search}%")
                      ->orWhere('whatsapp', 'like', "%{$search}%")
                      ->orWhere('address', 'like', "%{$search}%");
            })
            ->latest()
            ->paginate(10)
            ->withQueryString();

        return view('admin.customers.index', compact('customers', 'search'));
    }

    public function create()
    {
        return view('admin.customers.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20|unique:customers,whatsapp',
            'address' => 'nullable|string',
            'email' => 'nullable|email|unique:users,email',
            'password' => 'nullable|string|min:6',
        ]);

        $userId = null;
        if ($request->filled('email') && $request->filled('password')) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'customer',
            ]);
            $userId = $user->id;
        }

        Customer::create([
            'user_id' => $userId,
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Data Customer berhasil ditambahkan!');
    }

    public function edit(Customer $customer)
    {
        return view('admin.customers.edit', compact('customer'));
    }

    public function update(Request $request, Customer $customer)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'whatsapp' => 'required|string|max:20|unique:customers,whatsapp,' . $customer->id,
            'address' => 'nullable|string',
        ]);

        $customer->update([
            'name' => $request->name,
            'whatsapp' => $request->whatsapp,
            'address' => $request->address,
        ]);

        return redirect()->route('admin.customers.index')->with('success', 'Data Customer berhasil diperbarui!');
    }

    public function destroy(Customer $customer)
    {
        $customer->delete();
        return redirect()->route('admin.customers.index')->with('success', 'Data Customer berhasil dihapus!');
    }
}
