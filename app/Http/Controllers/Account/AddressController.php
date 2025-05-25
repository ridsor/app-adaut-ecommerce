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
            'recipient_name' => 'required|min:3|max:50',
            'full_address' => 'required',
            'note' => 'nullable',
            'province_name' => 'nullable',
            'city_name' => 'nullable',
            'district_name' => 'nullable',
            'subdistrict_name' => 'nullable',
            'zip_code' => 'nullable',
            'address_label' => 'nullable',
            'destination_id' => 'nullable',
            'phone_number' => [
                'required',
                new MultiCountryPhoneNumber,
            ],
        ];

        if ($request->address_label != $user->address?->address_label) {
            $rules['province_name'] = "required";
            $rules['city_name'] = "required";
            $rules['district_name'] = "required";
            $rules['subdistrict_name'] = "required";
            $rules['zip_code'] = "required";
            $rules['address_label'] = "required";
            $rules['destination_id'] = "required";
        }

        $validated = $request->validate($rules, [
            'recipient_name.required' => 'Nama Penerima wajib diisi.',
            'recipient_name.min' => 'Nama Penerima minimal berisi 3 karakter.',
            'recipient_name.max' => 'Nama Penerima maksimal berisi 50 karakter.',
            'phone_number.required' => 'Nomor telepon Penerima wajib diisi.',
            'full_address.required' => 'Alamat Lengkap Penerima wajib diisi.',
        ]);

        if ($user->address) {
            $user->address->update($validated);
        } else {
            $user->address()->create($validated);
        }

        return back()->with(['success' => 'Alamat berhasil disimpan']);
    }
}
