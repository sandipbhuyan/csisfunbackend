<?php

namespace App\Http\Controllers;

use App\Categories;
use App\Post;
use App\Type;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PublicEndpoint extends Controller
{
    public function getAllCategory() {
        return Categories::where("is_published", 1)->get();
    }

    public function getAllType() {
        return Type::where("is_published", 1)->get();
    }

    public function getPosts() {
        return Post::orderBy('id', 'desc')->where("is_published", 1)->get();
    }

    public function filterPost(Request $request) {
        return Post::orderBy('id', 'desc')->where("is_published", 1)->where('type_id', $request->type)->where('category_id', $request->category)->get();
    }

    public function getPost($id) {
        return Post::find($id);
    }
}
