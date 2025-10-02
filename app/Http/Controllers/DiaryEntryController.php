<?php

namespace App\Http\Controllers;

use App\Models\DiaryEntry;
use App\Models\Emotion;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Attributes\Middleware;

#[Middleware('auth')]
class DiaryEntryController extends Controller
{
    /**
     * หน้า My Diary + Summary + กรองด้วย ?emotion= (1..5)
     */
    public function index(Request $request)
    {
        $emotionFilter = (int) $request->query('emotion', 0);

        // รายการไดอารีของผู้ใช้ (กรองด้วย emotion ถ้ามี) + แบ่งหน้า
        $diaryEntries = Auth::user()
            ->diaryEntries()
            ->with(['emotions', 'tags'])
            ->when($emotionFilter > 0, function ($q) use ($emotionFilter) {
                $q->whereHas('emotions', function ($qq) use ($emotionFilter) {
                    $qq->where('emotions.id', $emotionFilter);
                });
            })
            ->orderByDesc('date')
            ->paginate(5)
            ->appends($request->query());

        // สรุปจำนวนไดอารีต่อ emotion (1..5)
        $counts = DB::table('diary_entry_emotions as dee')
            ->join('diary_entries as de', 'dee.diary_entry_id', '=', 'de.id')
            ->where('de.user_id', Auth::id())
            ->whereIn('dee.emotion_id', [1, 2, 3, 4, 5])
            ->select('dee.emotion_id', DB::raw('COUNT(*) as diary_count'))
            ->groupBy('dee.emotion_id')
            ->pluck('diary_count', 'dee.emotion_id')
            ->toArray();

        // ให้คีย์ครบ 1..5 เสมอ
        $summary = [1 => 0, 2 => 0, 3 => 0, 4 => 0, 5 => 0];
        foreach ($counts as $emotionId => $count) {
            $summary[(int) $emotionId] = (int) $count;
        }

        return view('diary.index', compact('diaryEntries', 'summary', 'emotionFilter'));
    }

    /**
     * Conflicting Emotions:
     * แสดงไดอารีที่ติดอารมณ์ "Sad" แต่มีคำว่า "happy" ใน content
     * - หา id ของ Sad จากตาราง emotions (ไม่ hardcode)
     * - ใช้ LIKE/ILIKE ตามไดรเวอร์ฐานข้อมูล
     */
    public function conflicts(Request $request)
    {
        $userId = Auth::id();

        // หา emotion id ของ "Sad" แบบไดนามิก (ไม่ผูกว่าเป็นเลข 2)
        $sadEmotionId = DB::table('emotions')->where('name', 'Sad')->value('id');

        // ถ้าไม่พบอารมณ์ชื่อ Sad -> คืนลิสต์ว่าง
        if (!$sadEmotionId) {
            $conflicts = DB::table('diary_entries')->whereRaw('1=0')->paginate(10);
            return view('diary.conflicts', compact('conflicts'));
        }

        // กำหนดเงื่อนไข like ให้ถูกกับไดรเวอร์
        $driver = DB::getDriverName(); // mysql | pgsql | sqlite | sqlsrv ...
        $contentClause = ($driver === 'pgsql') ? 'de.content ILIKE ?' : 'de.content LIKE ?';

        $conflicts = DB::table('diary_entries as de')
            ->join('diary_entry_emotions as dee', 'dee.diary_entry_id', '=', 'de.id')
            ->join('emotions as e', 'e.id', '=', 'dee.emotion_id')
            ->where('de.user_id', $userId)
            ->where('dee.emotion_id', $sadEmotionId)       // Sad
            ->whereRaw($contentClause, ['%happy%'])        // มีคำว่า happy
            ->select([
                'de.id',
                'de.date',
                'de.content',
                'e.name as emotion_name',
                'dee.intensity',
            ])
            ->orderByDesc('de.date')
            ->paginate(10)
            ->appends($request->query());

        return view('diary.conflicts', compact('conflicts'));
    }

    public function create()
    {
        $emotions = Emotion::all();
        $tags     = Tag::all();
        return view('diary.create', compact('emotions', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'        => ['required', 'date'],
            'content'     => ['required', 'string'],
            'emotions'    => ['nullable', 'array'],
            'emotions.*'  => ['integer', 'exists:emotions,id'],
            'intensity'   => ['nullable', 'array'],
            'intensity.*' => ['nullable', 'integer', 'min:1', 'max:10'],
            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['integer', 'exists:tags,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $entry = Auth::user()->diaryEntries()->create([
                'date'    => $validated['date'],
                'content' => $validated['content'],
            ]);

            // แนบอารมณ์ + intensity ลง pivot
            if (!empty($validated['emotions'])) {
                $attach = [];
                foreach ($validated['emotions'] as $emotionId) {
                    $attach[$emotionId] = [
                        'intensity' => $validated['intensity'][$emotionId] ?? null,
                    ];
                }
                $entry->emotions()->attach($attach);
            }

            // แนบแท็ก (polymorphic)
            $entry->tags()->sync($validated['tags'] ?? []);
        });

        return redirect()->route('diary.index')->with('status', 'Diary entry created.');
    }

    public function show(DiaryEntry $diary)
    {
        $this->authorize('view', $diary);
        $diary->load(['emotions', 'tags']);

        return view('diary.show', ['diaryEntry' => $diary]);
    }

    public function edit(DiaryEntry $diary)
    {
        $this->authorize('update', $diary);
        $emotions = Emotion::all();
        $tags     = Tag::all();
        $diary->load(['emotions', 'tags']);

        return view('diary.edit', [
            'diaryEntry' => $diary,
            'emotions'   => $emotions,
            'tags'       => $tags,
        ]);
    }

    public function update(Request $request, DiaryEntry $diary)
    {
        $this->authorize('update', $diary);

        $validated = $request->validate([
            'date'        => ['required', 'date'],
            'content'     => ['required', 'string'],
            'emotions'    => ['nullable', 'array'],
            'emotions.*'  => ['integer', 'exists:emotions,id'],
            'intensity'   => ['nullable', 'array'],
            'intensity.*' => ['nullable', 'integer', 'min:1', 'max:10'],
            'tags'        => ['nullable', 'array'],
            'tags.*'      => ['integer', 'exists:tags,id'],
        ]);

        DB::transaction(function () use ($validated, $diary) {
            $diary->update([
                'date'    => $validated['date'],
                'content' => $validated['content'],
            ]);

            // sync emotions + intensity
            if (!empty($validated['emotions'])) {
                $sync = [];
                foreach ($validated['emotions'] as $emotionId) {
                    $sync[$emotionId] = [
                        'intensity' => $validated['intensity'][$emotionId] ?? null,
                    ];
                }
                $diary->emotions()->sync($sync);
            } else {
                $diary->emotions()->sync([]);
            }

            // sync แท็ก
            $diary->tags()->sync($validated['tags'] ?? []);
        });

        return redirect()->route('diary.index')->with('status', 'Diary entry updated.');
    }

    public function destroy(DiaryEntry $diary)
    {
        $this->authorize('delete', $diary);
        $diary->delete();

        return redirect()->route('diary.index')->with('status', 'Diary entry deleted.');
    }
}
