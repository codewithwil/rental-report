<?php

namespace App\Http\Controllers\API\Resources\Category;

use App\{
    Http\Controllers\Controller,
    Models\Resources\Category\Category,
    Models\Resources\Company\Company,
    Traits\DbBeginTransac,
    Models\History\ActivityLog\ActivityLog,
};

use Illuminate\{
    Http\Request,
    Support\Facades\Validator
};


class CategoryC extends Controller
{
    use DbBeginTransac;
    public function index(){
        $category = Category::where('status', Category::STATUS_ACTIVE)
                            ->orderBy('created_at', 'asc')
                            ->get();
        return view('admin.resources.category.index', compact('category'));
    }

    public function invoice(){
        $category = Category::where('status', Category::STATUS_ACTIVE)->orderBy('created_at', 'asc')->get();
        $company  = Company::first();
        return view('admin.resources.category.invoice', compact('category', 'company'));
    }

    public function create(){
        return view('admin.resources.category.create');
    }

    public function edit($categoryId){
        $category = Category::findOrFail($categoryId);
        return view('admin.resources.category.update', compact('category'));
    }

    public function store(Request $req)
    {
        return $this->executeTransaction(function () use ($req) {
            $validator = Validator::make($req->all(), [
                'name'  => 'required|string|min:3|max:50',
                'type'  => 'required|in:' . Category::TYPE_CAR . ',' . Category::TYPE_MOTORCYCLE,
            ]);

            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $category = Category::create([
                'name' => $req->input('name'),
                'type' => $req->input('type'),
            ]);

            $category->logActivity(
                ActivityLog::ACTION_CREATE,
                "Kategori {$category->name} berhasil ditambahkan"
            );

            return response()->json(['success' => true, 'message' => 'Data Kategori berhasil ditambahkan!']);
        });
    }

    public function update(Request $req, $categoryId)
    {
        $req->validate([
            'name' => 'nullable|string|min:3|max:50',
            'type' => 'nullable|in:' . Category::TYPE_CAR . ',' . Category::TYPE_MOTORCYCLE,
        ]);

        return $this->executeTransaction(function () use ($req, $categoryId) {
            $category = Category::findOrFail($categoryId);
            $category->name = $req->name;
            $category->type = $req->type;
            $category->save();
            $category->logActivity(
                ActivityLog::ACTION_UPDATE,
                "Kategori {$category->name} berhasil diperbarui"
            );
            
            return redirect('/setting/category/')
                ->with('success', 'Data Kategori berhasil diperbarui.');
        });
    }


    public function delete($categoryId)
    {
        try {
            $category = Category::findOrFail($categoryId);
            $category->status = Category::STATUS_INACTIVE;
            $category->save();

            $category->logActivity(
               ActivityLog::ACTION_DELETE,
                "Kategori {$category->name} telah dihapus"
            );

            return redirect('/setting/category/')
                ->with('success', 'Data Kategori Berhasil Dihapus');
        } catch (\Exception $e) {
            return redirect('/setting/category/')
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
}

