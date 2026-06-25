<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    /**
     * Mostra a tela de criação de categoria.
     */
    public function show()
    {
        $user = Auth::user();

        $category = $user->category()->withCount('task')->orderBy('name')->get();

        return view('dashboard', compact('category'));
    }

    public function create()
    {
        return view('category.category-create');
    }

    /**
     * Salva uma nova categoria no banco.
     */
    public function store(Request $request)
    {
        /**
         * Validação dos dados enviados pelo formulário.
         *
         * name:
         * - obrigatório;
         * - texto;
         * - máximo de 60 caracteres.
         *
         * color:
         * - obrigatório;
         * - só pode ser uma das cores permitidas.
         */
        $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
            ],

            'color' => [
                'required',
                'in:blue,green,yellow,red,purple,gray',
            ],
        ]);

        /**
         * Criamos um slug a partir do nome da categoria.
         *
         * Exemplo:
         * "Estudo de Laravel" vira "estudo-de-laravel".
         */
        $slug = Str::slug($request->name);

        /**
         * Verifica se o usuário já tem uma categoria com esse mesmo slug.
         *
         * Isso evita categorias duplicadas como:
         * Study
         * study
         * Study!
         */
        $categoryExists = Auth::user()
            ->category()
            ->where('slug', $slug)
            ->exists();

        if ($categoryExists) {
            return back()
                ->withErrors([
                    'name' => 'You already have a category with this name.',
                ])
                ->withInput();
        }

        /**
         * Cria a categoria vinculada ao usuário logado.
         */
        Auth::user()->category()->create([
            'name' => $request->name,
            'slug' => $slug,
            'color' => $request->color,
        ]);

        /**
         * Depois de criar a categoria, o usuário é enviado para criar uma tarefa.
         */
        return redirect()
            ->route('dashboard')
            ->with('success', 'Category created successfully!');
    }

    public function edit(Category $category)
    {
        if($category->user_id !== Auth::id()) abort(403);

        return view('category.category-edit', compact('category'));
    }

    public function update(Category $category, Request $request)
    {
        if($category->user_id !== Auth::id()) abort(403);

        $request->validate([
            'name' => [
                'required',
                'string',
                'max:60',
            ],

            'color' => [
                'required',
                'in:blue,green,yellow,red,purple,gray',
            ],
        ]);

        $slug = Str::slug($request->name);

        $categoryExists = Auth::user()
            ->category()
            ->where('slug', $slug)
            ->where('id', '!=', $category->id)
            ->exists();

        if ($categoryExists) {
            return back()
                ->withErrors([
                    'name' => 'You already have another category with this name.',
                ])
                ->withInput();
        }

        /**
         * Atualiza a categoria.
         */
        $category->update([
            'name' => $request->name,
            'slug' => $slug,
            'color' => $request->color,
        ]);

        return redirect()
            ->route('dashboard')
            ->with('success', 'Category updated successfully!');

    }

    public function destroy(Category $category)
    {
        if($category->user_id !== Auth::id()) abort(403);

        if($category->task()->exists())
        {
            return back()->withErrors([
                'category' => 'You cannot delete this category because it has tasks linked to it.'
            ]);
        }

        $category->delete();

        return redirect()->route('dashboard')->with('success', 'Category deleted successfully!');


    }
}