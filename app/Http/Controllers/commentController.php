<?php

namespace App\Http\Controllers;
use App\Model\Comment;
use App\Model\Publication;
use Illuminate\Http\Request;
use JWTAuth;
use DB;
class commentController extends Controller
{
    public function getUser()
    {
           return  JWTAuth::parseToken()->authenticate();
    }
    public function store(Request $request)
    {
        $comment=new Comment();
        $comment->idUser=$this->getUser()->id;
        $comment->idPublication=$request->publication;
        $comment->commentaire=$request->commentaire;
        $comment->save();
        return $comment;
    }
    public function show($id)
    {
        $comments=DB::table('comments')
        ->join('publications','publications.id','comments.idPublication')
        ->selectRaw('comments.id,name,email,commentaire,contenu,comments.created_at,bestAnswer')
        ->join('users','users.id','comments.idUser')
        ->where('idPublication',$id)->get();
        $publication=Publication::find($id);
        return response()->json([
            "publications"=>$publication,
             "comments"=>$comments
        ]);
    }
}
