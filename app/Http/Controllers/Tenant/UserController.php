<?php
namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
 
class UserController extends Controller
{
    public function index(Request $request)
    {
        return $request->all();
    }
}