<?php

namespace App\Http\Controllers;
use JWTAuth;
use DB;
use Illuminate\Http\Request;
use App\Model\Listdomaine;
class listdomaineController extends Controller
{
    protected $user;
    public function __construct()
    {
        $this->user=JWTAuth::parseToken()->authenticate()->id;
    }
    public function store(Request $request)
    {
        $listDomaineIfexist=ListDomaine::where('idUser',$this->user)
        ->where('idDomaine',$request->domaine)->first();
        if($listDomaineIfexist)
        {
            return response()->json([
                "status"=>false,
                "message"=>"Vous êtes déjà dans cette domaine"
            ]);
        }
        $listDomaine=new ListDomaine();
        $listDomaine->idUser=$this->user;
        $listDomaine->idDomaine=$request->domaine;
        $listDomaine->save();
        return $listDomaine;
    }
    public function delete($id)
    {
        $listDomaine=Listdomaine::find($id);
        $listDomaine->delete();
        return 204;
    }
    public function index()
    {
        $listDomaines=DB::table('listdomaines')
        ->join('domaines','domaines.id','idDomaine')
        ->selectRaw('listdomaines.id,nomDomaine,idDomaine')
        ->where('idUser',$this->user)
        ->get();
        return $listDomaines;
    }

}
