<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class AdminProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'images')->latest()->get();

        return view('admin.products.index', ['products' => $products]);
    }

    public function create()
    {
        return view('admin.products.create', [
            'product'    => new Product(),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request, true);
        $data['slug'] = $this->uniqueSlug($data['name']);
        $data['image'] = '';

        $product = Product::create($data);
        $this->saveImages($product, $request->file('images', []));

        return redirect()
            ->route('admin.products.index')
            ->with('success', "{$product->name} was added.");
    }

    public function edit(Product $product)
    {
        $product->load('images');

        return view('admin.products.edit', [
            'product'    => $product,
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Product $product)
    {
        $data = $this->validated($request, false);

        // Photos to remove and photos to add
        $removeIds = collect($request->input('remove_images', []))->map(fn ($id) => (int) $id)->all();
        $removing  = $product->images()->whereIn('id', $removeIds)->get();
        $newFiles  = $request->file('images', []);

        $remaining = $product->images()->count() - $removing->count() + count($newFiles);

        if ($remaining < 3) {
            return back()
                ->withInput()
                ->withErrors(['images' => "Each product needs at least 3 photos. It would only have {$remaining}."]);
        }

        // The slug stays the same so existing links keep working
        $product->update($data);

        foreach ($removing as $image) {
            $this->deleteFile($image->path);
            $image->delete();
        }

        $this->saveImages($product, $newFiles);

        return redirect()
            ->route('admin.products.index')
            ->with('success', "{$product->name} was updated.");
    }

    public function destroy(Product $product)
    {
        $paths = $product->images()->pluck('path')->all();

        try {
            $product->delete();
        } catch (QueryException $e) {
            return back()->with('error', "{$product->name} cannot be deleted because it is used in existing orders. Set its stock to 0 instead.");
        }

        foreach ($paths as $path) {
            $this->deleteFile($path);
        }

        return redirect()
            ->route('admin.products.index')
            ->with('success', "{$product->name} was deleted.");
    }

    private function validated(Request $request, bool $imagesRequired): array
    {
        $data = $request->validate([
            'category_id'     => ['required', 'exists:categories,id'],
            'name'            => ['required', 'string', 'max:255'],
            'description'     => ['required', 'string', 'max:2000'],
            'price'           => ['required', 'numeric', 'min:0', 'max:99999'],
            'stock'           => ['required', 'integer', 'min:0', 'max:100000'],
            'collection'      => ['nullable', 'string', 'max:255'],
            'drop_ends_at'    => ['nullable', 'date'],
            'images'          => $imagesRequired
                                    ? ['required', 'array', 'min:3', 'max:10']
                                    : ['nullable', 'array', 'max:10'],
            'images.*'        => ['image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'remove_images'   => ['nullable', 'array'],
            'remove_images.*' => ['integer'],
        ], [
            'images.required'   => 'Please upload at least 3 photos.',
            'images.min'        => 'Please upload at least 3 photos.',
            'images.max'        => 'You can upload up to 10 photos at a time.',
            'images.*.image'    => 'Each file must be an image (JPG, PNG or WEBP).',
            'images.*.mimes'    => 'Each file must be a JPG, PNG or WEBP image.',
            'images.*.max'      => 'Each photo must be 2 MB or smaller.',
            'images.*.uploaded' => 'A photo failed to upload. Keep each photo under 2 MB.',
        ]);

        $data['is_limited']  = $request->boolean('is_limited');
        $data['is_featured'] = $request->boolean('is_featured');

        return Arr::except($data, ['images', 'remove_images']);
    }

    private function saveImages(Product $product, array $files): void
    {
        if (empty($files)) {
            return;
        }

        $folder = public_path('images/products');

        if (! is_dir($folder)) {
            mkdir($folder, 0755, true);
        }

        $order = (int) $product->images()->max('sort_order');

        foreach ($files as $file) {
            $name = $product->slug . '-' . Str::random(8) . '.' . $file->extension();
            $file->move($folder, $name);

            $product->images()->create([
                'path'       => 'images/products/' . $name,
                'sort_order' => ++$order,
            ]);
        }
    }

    private function deleteFile(string $path): void
    {
        $full = public_path($path);

        if (is_file($full)) {
            @unlink($full);
        }
    }

    private function uniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'product';
        $slug = $base;
        $number = 2;

        while (Product::where('slug', $slug)->exists()) {
            $slug = $base . '-' . $number++;
        }

        return $slug;
    }
}