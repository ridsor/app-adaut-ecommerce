<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $categories = Category::select('name','icon','id')->get();
        return view('admin.category.index', [
            'title' => "Kagtegori",
            "categories" => $categories
          ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.category.create', [
        "title" => 'Tambah Kategori',
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "name" => "required",
            "image" => "required|image|mimes:jpeg,png,jpg,svg,webp|max:1048",
          ],
            [

              'name.required' => 'Nama produk wajib diisi',
              'image.required' => 'Gambar produk wajib diunggah',
              'image.image' => 'File harus berupa gambar',
              'image.mimes' => 'Format gambar harus webp, svg, jpeg, png, atau jpg',
              'image.max' => 'Ukuran gambar maksimal 1MB',
          ]);
      
          $imageName = time() . '.' . $request->image->extension();
          if (!Storage::disk('public')->exists('icon/kategori')) {
              Storage::disk('public')->makeDirectory('icon/kategori');
          }
          $imageStorage = Storage::disk('public')->putFileAs('icon/kategori', $request->image, $imageName);
          $image = Storage::url($imageStorage);
      
          Category::create([
            "name" => $request->name,
            "icon" => $image,
          ]);
      
          return back()->with('success', 'Kategori berhasil dibuat');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $category = Category::find($id);

        return view('admin.category.edit', [
            "category" => $category,
          ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $rules = [
            "name" => "required",
          ];
      
          if ($request->hasFile('image')) {
            $rules['image'] = "required|image|mimes:jpeg,png,jpg,svg,webp|max:1048";
          }
      
          $request->validate($rules,[
            'name.required' => 'Nama produk wajib diisi',
            'image.required' => 'Gambar produk wajib diunggah',
            'image.image' => 'File harus berupa gambar',
            'image.mimes' => 'Format gambar harus svg, webp, jpeg, png, atau jpg',
            'image.max' => 'Ukuran gambar maksimal 1MB',
          ]);
      
          $category = Category::find($id);

          $image = $request->image;
      
          if ($request->hasFile('image')) { 
            $imageName = time() . '.' . $request->image->extension();
            $imageName = time() . '.' . $request->image->extension();
            if (!Storage::disk('public')->exists('icon/kategori')) {
                Storage::disk('public')->makeDirectory('icon/kategori');
            }
            $imageStorage = Storage::disk('public')->putFileAs('icon/kategori', $request->image, $imageName);
            $image = Storage::url($imageStorage);
          }
      
          $category->update([
            "name" => $request->name,
            "image" => $image,
          ]);
      
          return back()->with('success', 'Kategori berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Category::destroy($id);

        return back()->with('success', 'Kategori berhasil dihapus');
    }
}
