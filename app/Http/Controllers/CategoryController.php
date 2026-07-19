<?php

namespace App\Http\Controllers;

use App\Http\Requests\Category\StoreCategoryRequest;
use App\Http\Requests\Category\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CategoryController extends Controller
{
    /**
     * @var list<string>
     */
    private array $iconOptions = [
        'bi-briefcase',
        'bi-book',
        'bi-person',
        'bi-heart-pulse',
        'bi-cart3',
        'bi-wallet2',
        'bi-house',
        'bi-lightning',
        'bi-star',
        'bi-tag',
    ];

    public function index(Request $request): View
    {
        $this->authorize('viewAny', Category::class);

        $categories = $request->user()
            ->categories()
            ->latest()
            ->paginate(10);

        return view('categories.index', compact('categories'));
    }

    public function create(): View
    {
        $this->authorize('create', Category::class);

        return view('categories.create', [
            'iconOptions' => $this->iconOptions,
        ]);
    }

    public function store(StoreCategoryRequest $request): RedirectResponse
    {
        $request->user()->categories()->create($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', __('Category created successfully.'));
    }

    public function edit(Category $category): View
    {
        $this->authorize('update', $category);

        return view('categories.edit', [
            'category' => $category,
            'iconOptions' => $this->iconOptions,
        ]);
    }

    public function update(UpdateCategoryRequest $request, Category $category): RedirectResponse
    {
        $category->update($request->validated());

        return redirect()
            ->route('categories.index')
            ->with('success', __('Category updated successfully.'));
    }

    public function destroy(Category $category): RedirectResponse
    {
        $this->authorize('delete', $category);

        $category->delete();

        return redirect()
            ->route('categories.index')
            ->with('success', __('Category deleted successfully.'));
    }
}
