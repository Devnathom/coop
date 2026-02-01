<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function index(Request $request)
    {
        $query = Member::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('member_code', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('is_active', $request->status === 'active');
        }

        $members = $query->latest()->paginate(15);
        return view('members.index', compact('members'));
    }

    public function create()
    {
        $memberCode = Member::generateMemberCode();
        return view('members.create', compact('memberCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_card' => 'nullable|string|size:13|unique:members',
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'share_amount' => 'nullable|numeric|min:0',
        ]);

        $validated['member_code'] = Member::generateMemberCode();
        $validated['join_date'] = now();
        $validated['share_amount'] = $validated['share_amount'] ?? 0;

        Member::create($validated);

        return redirect()->route('members.index')
            ->with('success', 'เพิ่มสมาชิกเรียบร้อยแล้ว');
    }

    public function show(Member $member)
    {
        $member->load(['sales' => function($q) {
            $q->latest()->limit(20);
        }, 'dividends', 'patronageRefunds']);
        
        return view('members.show', compact('member'));
    }

    public function edit(Member $member)
    {
        return view('members.edit', compact('member'));
    }

    public function update(Request $request, Member $member)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'id_card' => 'nullable|string|size:13|unique:members,id_card,' . $member->id,
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string',
            'share_amount' => 'nullable|numeric|min:0',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');
        $member->update($validated);

        return redirect()->route('members.index')
            ->with('success', 'แก้ไขข้อมูลสมาชิกเรียบร้อยแล้ว');
    }

    public function destroy(Member $member)
    {
        $member->delete();
        return redirect()->route('members.index')
            ->with('success', 'ลบสมาชิกเรียบร้อยแล้ว');
    }

    public function search(Request $request)
    {
        $term = $request->get('term');
        $members = Member::where('is_active', true)
            ->where(function($q) use ($term) {
                $q->where('name', 'like', "%{$term}%")
                  ->orWhere('member_code', 'like', "%{$term}%")
                  ->orWhere('phone', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get(['id', 'member_code', 'name', 'phone']);

        return response()->json($members);
    }
}
