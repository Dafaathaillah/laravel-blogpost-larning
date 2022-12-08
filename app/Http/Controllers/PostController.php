<?php

namespace App\Http\Controllers;

use App\Models\KategoriPost;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PostController extends Controller
{
    public function index(Request $req){
        if ($req->ajax()) {
            $data = Post::with(['kategori', 'usr'])->latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" id="btnEdit" data-id="' . $row->id_post . '" class="btn btn-warning btn-sm shadow"><i class="fa fa-pencil fa-sm"></i></a>';
                        $btn = $btn . ' <a href="javascript:void(0)" id="btnDelete" data-id="' . $row->id_post . '#' . $row->nama_kategori . '" class="btn btn-danger btn-sm shadow"><i class="fa fa-trash fa-sm"></i></a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        $kategoris = KategoriPost::all();
        $user = User::all();
        return view('admin.post.post', compact('kategoris', 'user'));
    }

    public function show(Post $post)
    {
        return response()->json($post->load(['kategori', 'usr']));
    }

    public function save(Request $req){
        $validateData = $req->validate([
            'judul_post' => 'required|max:255',
            'kategori_id' => 'required',
            'user_id' => 'required',
        ]);

        // if (empty($req->id)) {
        //     $data = KategoriPost::create($validateData);
        // }else{
        //     $data = KategoriPost::where('id_kategori', $req->id)->update($validateData);
        // }
            
        $data = Post::updateOrCreate(['id_post' => $req->id], $validateData);

        return response()->json($data);
    }

    public function destroy(Post $post)
    {

        $data = $post->delete();
        return response()->json($data);
    }
}
