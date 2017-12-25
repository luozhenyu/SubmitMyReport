<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Group;

class AssignmentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
}
