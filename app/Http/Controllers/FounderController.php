<?php

namespace App\Http\Controllers;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Schools\Teacher;
use App\Models\User;
use Illuminate\Http\Request;

class FounderController extends Controller
{
    public function __invoke()
    {
        $dto = [];
        $dto['header'] = 'founder page';
        $dto['users'] = Teacher::query()
            ->join('users', 'users.id', '=', 'teachers.user_id')
            ->orderBy('users.last_name')->orderBy('users.first_name')->get();
        return view('pages.foundersPage', compact('dto'));
    }
}
