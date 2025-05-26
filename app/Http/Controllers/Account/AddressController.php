<?php

namespace App\Http\Controllers\Account;

use App\Http\Controllers\Controller;
use App\Rules\MultiCountryPhoneNumber;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function __construct()
    {
        $this->authorize('isUser');
    }

    public function index(Request $request)
    {
        $address = $request->user()->address;
        return view('account.address', [
            'title' => 'Akun - Alamat',
            'address' => $address
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $user = $request->user();

        $rules = [
            'name' => 'required|min:3|max:50',
            'address' => 'required',
            'note' => 'nullable',
            'province_name' => 'required',
            'city_name' => 'required',
            'district_name' => 'required',
            'subdistrict_name' => 'required',
            'zip_code' => 'required',
            'address_label' => 'required',
            'destination_id' => 'required',
            'phone_number' => [
                'required',
                new MultiCountryPhoneNumber,
            ],
        ];


        $validated = $request->validate($rules, [
            'name.required' => 'Nama Penerima wajib diisi.',
            'name.min' => 'Nama Penerima minimal berisi 3 karakter.',
            'name.max' => 'Nama Penerima maksimal berisi 50 karakter.',
            'phone_number.required' => 'Nomor telepon Penerima wajib diisi.',
            'address.required' => 'Alamat Lengkap Penerima wajib diisi.',
        ]);

        if ($request->address_label == $user->address?->address_label) {
            $validated['province_name'] = $user->address->province_name;
            $validated['city_name'] = $user->address->city_name;
            $validated['district_name'] = $user->address->district_name;
            $validated['subdistrict_name'] = $user->address->subdistrict_name;
            $validated['zip_code'] = $user->address->zip_code;
            $validated['address_label'] = $user->address->address_label;
            $validated['destination_id'] = $user->address->destination_id;
        }

        if ($user->address) {
            $user->address->update($validated);
        } else {
            $user->address()->create($validated);
        }

        return back()->with(['success' => 'Alamat berhasil disimpan']);
    }
}