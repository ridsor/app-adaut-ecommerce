<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions\ItemNotFoundException;
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

  public function index(Request $request)
  {
    $categories = Category::search($request->query('search'))->query(fn($query) => $query->select(['name', 'icon', 'slug', 'id'])->withCount('products')->latest())->get();
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
      $icon = $request->file('icon')->store('ikon/kategori');

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
  public function edit($slug)
  {
    $category = Category::where('slug', $slug)->first();
    if (!$category) {
      throw new ItemNotFoundException($slug);
    }

    return view('admin.category.edit', [
      'title' => 'Edit Kategori ' . $category->name,
      "category" => $category,
    ]);
  }

  /**
   * Update the specified resource in storage.
   */
  public function update(Request $request, string $slug)
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

      $category = Category::where('slug', $slug)->firstOrFail();

      $icon = $category->icon;

      if ($request->hasFile('icon')) {
        if ($icon) {
          Storage::delete($icon);
        }

        // Simpan gambar yang diunggah
        $icon = $request->file('icon')->store('ikon/kategori');
      }

      $category->update([
        "name" => $request->name,
        "icon" => $icon,
      ]);

      return redirect(route('category.index'))->with('success', 'Kategori berhasil diperbarui');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal memperbarui kategori');
    }
  }

  /**
   * Remove the specified resource from storage.
   */
  public function destroy(string $slug)
  {
    try {
      $category = Category::where('slug', $slug)->firstOrFail();
      if ($category->icon) {
        Storage::delete($category->icon);
      }
      $category->delete();

      return back()->with('success', 'Kategori berhasil dihapus');
    } catch (\Exception $e) {
      return back()->with('error', 'Gagal menghapus kategori');
    }
  }
}