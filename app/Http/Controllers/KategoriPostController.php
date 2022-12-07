<?php

namespace App\Http\Controllers;

use App\Models\KategoriPost;
use Illuminate\Http\Request;
use DataTables;

class KategoriPostController extends Controller
{
    public function index(Request $req){
        if ($req->ajax()) {
            $data = KategoriPost::latest()->get();
                return DataTables::of($data)
                    ->addIndexColumn()
                    ->addColumn('action', function ($row) {
                        $btn = '<a href="javascript:void(0)" id="btnEdit" data-id="' . $row->id_kategori . '" class="btn btn-warning btn-sm shadow"><i class="fa fa-pencil fa-sm"></i></a>';
                        $btn = $btn . ' <a href="javascript:void(0)" id="btnDelete" data-id="' . $row->id_kategori . '#' . $row->nama_kategori . '" class="btn btn-danger btn-sm shadow"><i class="fa fa-trash fa-sm"></i></a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
        }
        return view('admin.kategori.kategori');
    }

    public function save(Request $req){
        $validateData = $req->validate([
            'nama_kategori' => 'required|max:255',
        ]);

        // if (empty($req->id)) {
        //     $data = KategoriPost::create($validateData);
        // }else{
        //     $data = KategoriPost::where('id_kategori', $req->id)->update($validateData);
        // }
            
        $data = KategoriPost::updateOrCreate(['id_kategori' => $req->id], $validateData);

        return response()->json($data);
    }

    public function show(KategoriPost $kategori){
        return response()->json($kategori);
    }

    public function destroy(KategoriPost $kategori){
        $data = $kategori->delete();

        return response()->json($data);
    }
}
