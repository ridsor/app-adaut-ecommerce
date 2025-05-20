<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ItemNotFoundException;
use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct()
    {
        $this->authorize('isAdmin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $total_banner = Banner::count();
        $banners = Banner::search($request->query('search'))->query(fn($query) => $query->select(['id', 'image', 'title', 'description'])->latest())->get();

        return view('admin.banner.index', [
            'title' => 'Spanduk',
            'banners' => $banners,
            'total_banner' => $total_banner
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function show($id)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            throw new ItemNotFoundException($id);
        }
        return view('admin.banner.show', [
            'banner' => $banner
        ]);
    }

    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'description' => 'required',
            'image' => "required|image|mimes:jpeg,png,jpg,webp|max:1048",
            'button_text' => 'nullable',
            'button_link' => [
                'nullable',
                'required_with:button_text',
                'url',
                'max:255',
                function ($attribute, $value, $fail) use ($request) {
                    // Jika button_text kosong tapi button_link diisi
                    if (empty($request->input('button_text')) && !empty($value)) {
                        $fail('Button link tidak boleh diisi jika tombol teks kosong');
                    }
                }
            ]
        ], [
            'image.required' => 'Gambar produk wajib diunggah',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus web, jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 1MB',

            'title.required' => 'Title spanduk wajib diisi',
            'description.required' => 'Deskripsi produk wajib diisi',

            'button_link.required_with' => 'Button link wajib diisi jika button text ada isinya',
        ]);
        try {
            $image = FileHelper::uploadFile($request->image, 'gambar/spanduk');

            Banner::create([
                "title" => $request->title,
                "image" => $image,
                "description" => $request->description,
                "button_text" => $request->button_text,
                "button_link" => $request->button_link,
            ]);

            return redirect(route('banner.index'))->with('success', 'Spanduk berhasil dibuat');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat spanduk');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $banner = Banner::find($id);
        if (!$banner) {
            throw new ItemNotFoundException($id);
        }
        return view('admin.banner.edit', [
            "banner" => $banner
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $rules = [
            'title' => 'required',
            'description' => 'required',
            'button_text' => 'nullable',
            'button_link' => 'nullable',
        ];

        if ($request->hasFile('image')) {
            $rules['image'] = "required|image|mimes:webp,jpeg,png,jpg|max:1048";
        }

        $request->validate($rules, [
            'image.required' => 'Gambar produk wajib diunggah',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus web, jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 1MB',

            'title.required' => 'Title spanduk wajib diisi',
            'description.required' => 'Deskripsi produk wajib diisi',
        ]);

        try {
            $banner = $banner = Banner::findOrFail($id);

            $image = $banner->image;
            if ($request->hasFile('image')) {
                $image = FileHelper::uploadFile($request->image, 'gambar/spanduk');
            }

            $banner->update([
                "title" => $request->title,
                "image" => $image,
                "description" => $request->description,
                "button_text" => $request->button_text,
                "button_link" => $request->button_link,
            ]);

            return redirect(route('banner.index'))->with('success', 'Spanduk berhasil diedit');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal mengedit spanduk');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $banner = Banner::findOrFail($id);
            if ($banner->image) {
                FileHelper::deleteFileByUrl($banner->image);
            }
            $banner->delete();

            return back()->with('success', 'Spanduk berhasil dihapus');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal menghapus spanduk');
        }
    }
}