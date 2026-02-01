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

        // Summary data
        $summary = [
            'total_members' => Member::count(),
            'active_members' => Member::where('is_active', true)->count(),
            'inactive_members' => Member::where('is_active', false)->count(),
            'total_shares' => Member::sum('share_amount'),
            'total_purchases' => Member::sum('accumulated_purchase'),
        ];

        return view('members.index', compact('members', 'summary'));
    }

    public function create()
    {
        $memberCode = Member::generateMemberCode();
        return view('members.create', compact('memberCode'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'member_code' => 'required|string|max:20|unique:members',
            'name' => 'required|string|max:255',
            'class_level' => 'nullable|string|max:10',
            'room' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
            'share_amount' => 'nullable|numeric|min:0',
        ]);

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
            'member_code' => 'required|string|max:20|unique:members,member_code,' . $member->id,
            'name' => 'required|string|max:255',
            'class_level' => 'nullable|string|max:10',
            'room' => 'nullable|string|max:10',
            'email' => 'nullable|email|max:255',
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
                  ->orWhere('class_level', 'like', "%{$term}%");
            })
            ->limit(10)
            ->get(['id', 'member_code', 'name', 'class_level', 'room']);

        return response()->json($members);
    }

    // หน้าเลื่อนชั้นเรียน
    public function promoteIndex()
    {
        $classLevels = Member::$classLevels;
        $members = Member::where('is_active', true)
            ->whereIn('class_level', Member::$classOrder)
            ->orderBy('class_level')
            ->orderBy('room')
            ->get();

        return view('members.promote', compact('members', 'classLevels'));
    }

    // เลื่อนชั้นสมาชิกทีละคน
    public function promote(Member $member)
    {
        if ($member->promoteClass()) {
            return response()->json(['success' => true, 'new_class' => $member->class_level]);
        }
        return response()->json(['success' => false, 'message' => 'ไม่สามารถเลื่อนชั้นได้']);
    }

    // เลื่อนชั้นทั้งหมด
    public function promoteAll(Request $request)
    {
        $fromClass = $request->from_class;
        $members = Member::where('is_active', true)
            ->where('class_level', $fromClass)
            ->get();

        $promoted = 0;
        foreach ($members as $member) {
            if ($member->promoteClass()) {
                $promoted++;
            }
        }

        return redirect()->route('members.promote')
            ->with('success', "เลื่อนชั้นสมาชิกจาก {$fromClass} จำนวน {$promoted} คน");
    }

    // สำเร็จการศึกษา (ปิดสถานะ ม.6)
    public function graduate()
    {
        $graduated = Member::where('is_active', true)
            ->where('class_level', 'ม.6')
            ->update(['is_active' => false]);

        return redirect()->route('members.promote')
            ->with('success', "ปิดสถานะสมาชิกจบการศึกษา ม.6 จำนวน {$graduated} คน");
    }
}
