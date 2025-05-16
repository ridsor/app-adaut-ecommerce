<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */

  public function __construct()
  {
    $this->authorize('isAdmin');
  }

  public function index()
  {
    $categories = Category::select('name', 'icon', 'id')->get();
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
    try {
      $request->validate(
        [
          "name" => "required",
          "image" => "required|image|mimes:jpeg,png,jpg,svg,webp|max:500",
        ],
        [

          'name.required' => 'Nama produk wajib diisi',
          'image.required' => 'Gambar produk wajib diunggah',
          'image.image' => 'File harus berupa gambar',
          'image.mimes' => 'Format gambar harus webp, svg, jpeg, png, atau jpg',
          'image.max' => 'Ukuran gambar maksimal 500KB',
        ]
      );

      $image = FileHelper::uploadFile($request->image, 'icons/category');

      Category::create([
        "name" => $request->name,
        "icon" => $image,
      ]);

      return back()->with('success', 'Kategori berhasil dibuat');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal membuat kategori');
    }
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
    try {
      $rules = [
        "name" => "required",
      ];

      if ($request->hasFile('image')) {
        $rules['image'] = "required|image|mimes:jpeg,png,jpg,svg,webp|max:500";
      }

      $request->validate($rules, [
        'name.required' => 'Nama produk wajib diisi',
        'image.required' => 'Gambar produk wajib diunggah',
        'image.image' => 'File harus berupa gambar',
        'image.mimes' => 'Format gambar harus svg, webp, jpeg, png, atau jpg',
        'image.max' => 'Ukuran gambar maksimal 500KB',
      ]);

      $category = Category::find($id);

      $image = $request->image;

      if ($request->hasFile('image')) {
        $image = FileHelper::uploadFile($request->image, 'icon/kategori');
      }

      $category->update([
        "name" => $request->name,
        "image" => $image,
      ]);

      return back()->with('success', 'Kategori berhasil diperbarui');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal memperbarui kategori');
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $id)
  {
    try {
      $category = Category::find($id);
      if ($category->image) {
        FileHelper::deleteFileByUrl($category->image);
      }
      $category->delete();
  
      return back()->with('success', 'Kategori berhasil dihapus');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal menghapus kategori');
    }
  }
}
