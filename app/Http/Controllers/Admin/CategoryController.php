<?php

namespace App\Http\Controllers\Admin;

use App\Helpers\FileHelper;
use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
  /**
   * Display a listing of the resource.
   */

  public function __construct()
  {
    $this->authorize('isAdmin');
  }

  public function index(Request $request)
  {
    $categories = Category::search($request->query('search'))->query(fn($query) => $query->select(['name', 'icon', 'id'])->withCount('products')->latest())->get();
    $total_categories = Category::count();

    return view('admin.category.index', [
      'title' => "Kategori",
      "categories" => $categories,
      'total_categories' => $total_categories
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
    $request->validate(
      [
        "name" => "required",
        "icon" => "required|mimes:jpeg,png,jpg,svg,webp|max:500",
      ],
      [

        'name.required' => 'Nama produk wajib diisi',
        'icon.required' => 'Gambar produk wajib diunggah',
        'icon.mimes' => 'Format gambar harus webp, svg, jpeg, png, atau jpg',
        'icon.max' => 'Ukuran gambar maksimal 500KB',
      ]
    );
    try {
      $icon = FileHelper::uploadFile($request->icon, 'ikon/kategori');

      Category::create([
        "name" => $request->name,
        "icon" => $icon,
      ]);

      return redirect(route('category.index'))->with('success', 'Kategori berhasil dibuat');
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

      if ($request->hasFile('icon')) {
        $rules['icon'] = "required|mimes:jpeg,png,jpg,svg,webp|max:500";
      }

      $request->validate($rules, [
        'name.required' => 'Nama produk wajib diisi',
        'icon.required' => 'Gambar produk wajib diunggah',
        'icon.mimes' => 'Format gambar harus svg, webp, jpeg, png, atau jpg',
        'icon.max' => 'Ukuran gambar maksimal 500KB',
      ]);

      $category = Category::find($id);

      $icon = $category->icon;

      if ($request->hasFile('icon')) {
        $icon = FileHelper::uploadFile($request->icon, 'icon/kategori');
      }

      $category->update([
        "name" => $request->name,
        "icon" => $icon,
      ]);

      return redirect(route('category.index'))->with('success', 'Kategori berhasil diperbarui');
    } catch (\Exception $e) {
      dd($e);
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