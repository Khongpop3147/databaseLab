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
    public function index()
    {
        $diaryEntries = Auth::user()
            ->diaryEntries()
            ->with(['emotions', 'tags'])
            ->orderByDesc('date')
            ->get();

        return view('diary.index', compact('diaryEntries'));
    }

    public function create()
    {
        $emotions = Emotion::all();
        $tags     = Tag::all();                 // ✅ ส่งรายการแท็กไปที่ฟอร์ม
        return view('diary.create', compact('emotions', 'tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date'        => ['required','date'],
            'content'     => ['required','string'],
            'emotions'    => ['nullable','array'],
            'emotions.*'  => ['integer','exists:emotions,id'],
            'intensity'   => ['nullable','array'],
            'intensity.*' => ['nullable','integer','min:1','max:10'],
            'tags'        => ['nullable','array'],              // ✅ รับเป็น array ของ id
            'tags.*'      => ['integer','exists:tags,id'],
        ]);

        DB::transaction(function () use ($validated) {
            $entry = Auth::user()->diaryEntries()->create([
                'date'    => $validated['date'],
                'content' => $validated['content'],
            ]);

            // emotions + intensity
            if (!empty($validated['emotions'])) {
                $attach = [];
                foreach ($validated['emotions'] as $emotionId) {
                    $attach[$emotionId] = [
                        'intensity' => $validated['intensity'][$emotionId] ?? null,
                    ];
                }
                $entry->emotions()->attach($attach);
            }

            // ✅ แนบแท็กจาก checkbox
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
        $tags     = Tag::all();                 // ✅ ส่งรายการแท็กไปที่ฟอร์ม
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
            'date'        => ['required','date'],
            'content'     => ['required','string'],
            'emotions'    => ['nullable','array'],
            'emotions.*'  => ['integer','exists:emotions,id'],
            'intensity'   => ['nullable','array'],
            'intensity.*' => ['nullable','integer','min:1','max:10'],
            'tags'        => ['nullable','array'],              // ✅ array
            'tags.*'      => ['integer','exists:tags,id'],
        ]);

        DB::transaction(function () use ($validated, $diary) {
            $diary->update([
                'date'    => $validated['date'],
                'content' => $validated['content'],
            ]);

            // emotions
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

            // ✅ sync แท็กตาม checkbox
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
