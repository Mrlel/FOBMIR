<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\IndividusMenage;
use App\Models\Menage;
use App\Models\Document;
use App\Models\Village;
use App\Models\Quartier;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function index()
    {
        $users = User::all();
        $menages = Menage::all();
        $documents = Document::all();
        $villages = Village::all();
        $quartiers = Quartier::all();
        return view('Admin.dashboard', compact('users','menages','documents','villages','quartiers'));
    }
    public function dashboardPointFocal()
    {
        $individus = IndividusMenage::where('point_focal_id', Auth::user()->id)->get();
        return view('point_focal.dashboard', compact('individus'));
    }

    public function utilisateurs()
    {
        $users = User::all();
        return view('Admin.utilisateurs.index', compact('users'));
    }
    public function userSave(){
        
    }
}
