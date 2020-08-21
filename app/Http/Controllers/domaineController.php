<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Model\Domaine;
class domaineController extends Controller
{
    public function index()
    {
        return Domaine::All();
    }

}
