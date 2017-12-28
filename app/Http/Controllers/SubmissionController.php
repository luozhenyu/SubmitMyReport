<?php

namespace App\Http\Controllers;

use App\Models\Assignment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\Group;

class SubmissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

}
