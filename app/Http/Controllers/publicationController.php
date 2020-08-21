<?php

namespace App\Http\Controllers;
use JWTAuth;
use DB;
use Illuminate\Http\Request;
use App\Model\Publication;
class publicationController extends Controller
{
    public function getUser()
    {
           return  JWTAuth::parseToken()->authenticate();
    }
    public function index()
    {
        $publications=DB::table('publications')
        ->join('domaines','domaines.id','publications.idDomaine')
        ->join('listdomaines','listdomaines.idDomaine','domaines.id')
        ->join('users','users.id','publications.idUser')
        ->selectRaw('contenu,name,email,resolu,publications.id,nomDomaine')
        ->where('listdomaines.idUser',$this->getUser()->id)
        ->paginate(5);
        $nbComments=DB::table('publications')
        ->join('comments','comments.idPublication','publications.id')
        ->selectRaw('publications.id,count(comments.id) as nbComment')
        ->groupBy('publications.id')
        ->get();
        return response()->json([
            "publications"=>$publications,
             "nbComments"=>$nbComments
        ]);
    }
    public function rechercheAllPub($texte)
    {
        $publications=DB::table('publications')
        ->join('domaines','domaines.id','publications.idDomaine')
        ->join('users','users.id','publications.idUser')
        ->selectRaw('contenu,name,email,resolu,publications.id,nomDomaine')
        ->where(DB::raw('concat(contenu,name)'),'like','%'.$texte.'%')
        ->paginate(10);
        $nbComments=DB::table('publications')
        ->join('comments','comments.idPublication','publications.id')
        ->selectRaw('publications.id,count(comments.id) as nbComment')
        ->groupBy('publications.id')
        ->get();
        return response()->json([
            "publications"=>$publications,
             "nbComments"=>$nbComments
        ]);
    }
    public function allPub()
    {
        $publications=DB::table('publications')
        ->join('domaines','domaines.id','publications.idDomaine')
        ->join('users','users.id','publications.idUser')
        ->selectRaw('contenu,name,email,resolu,publications.id,nomDomaine')
        ->paginate(10);
        $nbComments=DB::table('publications')
        ->join('comments','comments.idPublication','publications.id')
        ->selectRaw('publications.id,count(comments.id) as nbComment')
        ->groupBy('publications.id')
        ->get();
        return response()->json([
            "publications"=>$publications,
             "nbComments"=>$nbComments
        ]);
    }
    public function pubsDomaine($idDomaine)
    {
        $publications=DB::table('publications')
        ->join('domaines','domaines.id','publications.idDomaine')
        ->join('users','users.id','publications.idUser')
        ->selectRaw('contenu,name,email,resolu,publications.id,nomDomaine')
        ->where('domaines.id',$idDomaine)
        ->paginate(5);
        $nbComments=DB::table('publications')
        ->join('comments','comments.idPublication','publications.id')
        ->selectRaw('publications.id,count(comments.id) as nbComment')
        ->groupBy('publications.id')
        ->get();
        return response()->json([
            "publications"=>$publications,
             "nbComments"=>$nbComments
        ]);
    }
    public function myPublications()
    {
        $publications=DB::table('publications')
        ->join('users','users.id','publications.idUser')
        ->where('users.id',$this->getUser()->id)
        ->selectRaw('publications.id,name,email,contenu,resolu')
        ->paginate(5);
        $nbComments=DB::table('publications')
        ->join('comments','comments.idPublication','publications.id')
        ->selectRaw('publications.id,count(comments.id) as nbComment,nomDomaine')
        ->groupBy('publications.id')
        ->get();
        return response()->json([
            "publications"=>$publications,
             "nbComments"=>$nbComments
        ]);
    }
    public function show($id)
    {
        return Publication::find($id);
    }
    public function store(Request $request)
    {
        $publication=new Publication();
        $publication->contenu=$request->contenu;
        $publication->idUser=$this->getUser()->id;
        $publication->idDomaine=$request->domaine;
        $publication->save();
        return response()->json([
            "publication"=>$publication,
             "status"=>true
        ]);
    }
    public function update(Request $request,$id)
    {
        $publication=Publication::findOrFail($id);
        $publication->contenu=$request->contenu;
        $publication->idDomaine=$request->domaine;
        $publication->save();
        return response()->json([
            "publication"=>$publication,
             "status"=>true
        ]);
    }
    public function delete($id)
    {
        $publication=Publication::findOrFail($id);
        $publication->delete();
        return 204;
    }
}
