<?php

namespace App\Traits\Libraries;

use App\Models\Libraries\Items\Components\LibItemLocation;
use App\Models\Libraries\LibLibrarian;
use App\Models\Libraries\Library;
use App\Models\Schools\Teacher;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;


trait LibrarianTeacherUserIdTrait
{
    public function getTeacherUserId(): int
    {
        if (auth()->user()->isTeacher()) {
            return auth()->user()->id;
        }

        if (auth()->user()->isLibrarian()) {
            return LibLibrarian::where('user_id', auth()->id())
                ->first()
                ->teacherUserId;
        }

        return 0;
    }
}
