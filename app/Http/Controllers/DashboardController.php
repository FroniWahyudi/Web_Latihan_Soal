<?php

namespace App\Http\Controllers;

use App\Models\MataKuliah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Menampilkan dashboard.
     */
    public function index()
    {
        $subjects = MataKuliah::all();
        return view('dashboard', compact('subjects'));
    }
}