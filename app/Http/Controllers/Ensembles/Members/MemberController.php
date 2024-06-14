<?php

namespace App\Http\Controllers\Ensembles\Members;

use App\Data\ViewDataFactory;
use App\Http\Controllers\Controller;
use App\Models\Ensembles\Members\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index()
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }

    public function edit(Member $member)
    {
        $data = new ViewDataFactory(__METHOD__, $member->id);

        $dto = $data->getDto();

        $id = $member->id;

        return view($dto['pageName'], compact('dto', 'id'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'ensemble_id' => ['required', 'exists:ensembles'],
            'student_id' => ['required', 'exists:students'],
            'school_year' => ['required', 'integer'],
            'status' => ['required'],
        ]);

        return Member::create($data);
    }

    public function create()
    {
        $data = new ViewDataFactory(__METHOD__);

        $dto = $data->getDto();

        return view($dto['pageName'], compact('dto'));
    }

    public function show(Member $member)
    {
        return $member;
    }

    public function update(Request $request, Member $member)
    {
        $data = $request->validate([
            'ensemble_id' => ['required', 'exists:ensembles'],
            'student_id' => ['required', 'exists:students'],
            'school_year' => ['required', 'integer'],
            'status' => ['required'],
        ]);

        $member->update($data);

        return $member;
    }

    public function destroy(Member $member)
    {
        $member->delete();

        return response()->json();
    }
}
