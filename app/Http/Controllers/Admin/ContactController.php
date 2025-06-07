<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function __construct()
    {
        $this->authorize('isAdmin');
    }

    public function index(Request $request)
    {
        $contacts = Contact::search($request->query('search'))->filter(request(['sort', 'status']))->paginate(10);

        $total_items = Contact::count();

        $sort = [
            [
                'value' => 'latest',
                'text' => 'Terbaru'
            ],
            [
                'value' => 'oldest',
                'text' => 'Terlama'
            ],
        ];

        $status = [
            [
                'value' => 'pending',
                'text' => 'Tertunda'
            ],
            [
                'value' => 'completed',
                'text' => 'Selesai'
            ],
        ];

        return view('admin.contact', [
            'title' => 'Kontak',
            'contacts' => $contacts,
            'total_items' => $total_items,
            'sort' => $sort,
            'status' => $status,
        ]);
    }
}
