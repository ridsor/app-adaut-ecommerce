<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Banner;
use Illuminate\Http\Request;

class BannerController extends Controller
{
    public function __construct() {
        $this->authorize('isAdmin');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::latest()->get();

        return view('admin.banner.index',[
            'title' => 'Spanduk',
            'banners' => $banners
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.banner.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'image' => "required|image|mimes:jpeg,png,jpg,webp|max:1048",
                'button_text' => 'nullable',
                'button_link' => 'nullable',
            ],[
                'image.required' => 'Gambar produk wajib diunggah',
                'image.image' => 'File harus berupa gambar',
                'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
                'image.max' => 'Ukuran gambar maksimal 1MB',

                'title.required' => 'Title spanduk wajib diisi',
                'description.required' => 'Deskripsi produk wajib diisi',
            ]);

            $image = FileHelper::uploadFile($request->image, 'gambar/spanduk');

            Banner::create([
            "title" => $request->name,
            "image" => $image,
            "description" => $request->description,
            "button_text" => $request->button_text,
            "button_link" => $request->button_link,
            ]);
        
            return back()->with('success', 'Spanduk berhasil dibuat');
        } catch (\Exception $e) {
            return back()->with('error', 'Gagal membuat spanduk');
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Banner $banner)
    {
        return view('admin.banner.edit', [
            $banner
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        try {
            $banner = $banner = Banner::findOrFail($id);

            $request->validate([
                'title' => 'required',
                'description' => 'required',
                'image' => "required|image|mimes:jpeg,png,jpg,webp|max:1048",
                'button_text' => 'nullable',
                'button_link' => 'nullable',
            ],[
                'image.required' => 'Gambar produk wajib diunggah',
                'image.image' => 'File harus berupa gambar',
                'image.mimes' => 'Format gambar harus jpeg, png, atau jpg',
                'image.max' => 'Ukuran gambar maksimal 1MB',

                'title.required' => 'Title spanduk wajib diisi',
                'description.required' => 'Deskripsi produk wajib diisi',
            ]);

            $image = FileHelper::uploadFile($request->image, 'images/product');

            $banner->update([
                "title" => $request->name,
                "image" => $image,
                "description" => $request->description,
                "button_text" => $request->button_text,
                "button_link" => $request->button_link,
            ]);

            return back()->with('success', 'Spanduk berhasil diedit');
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
