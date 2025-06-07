<?php

namespace App\Http\Controllers\Api\Admin;

use App\Http\Controllers\Api\BaseController;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class ContactController extends BaseController
{
    public function __construct()
    {
        $this->authorize('isAdmin');
    }

    public function update(Request $request)
    {
        try {
            $validator = Validator::make(
                $request->all(),
                [
                    'status' => 'required|in:pending,completed',
                    'items' => 'required|array',
                    'items.*.id' => ['required', Rule::exists('contacts', 'id')],
                ],
                [
                    'items.*.id.required' => 'ID wajib diisi.',
                ]
            );
            if ($validator->fails()) {
                return $this->sendError(error: 'Validasi gagal', errorMessages: $validator->errors(), code: 500);
            }

            $validated = $validator->validated();

            foreach ($validated['items'] as $item) {
                Contact::where('id', $item['id'])->update(['status' => $validated['status']]);
            }

            return $this->sendResponse("Berhasil memperbarui kontak");
        } catch (\Exception $e) {
            return $this->sendError(error: "Terjadi kesalahan pada server", code: 500);
        }
    }
}
