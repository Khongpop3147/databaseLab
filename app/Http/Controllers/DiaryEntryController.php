<?php

namespace App\Http\Controllers;

use App\Models\DiaryEntry;
use App\Models\Emotion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DiaryEntryController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $diaryEntries = Auth::user()->diaryEntries()->with('emotions')->get();
        return view('diary.index', compact('diaryEntries'));
    }

    public function create()
    {
        $emotions = Emotion::all(); // ถ้ามี emotions
        return view('diary.create', compact('emotions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
            'emotions' => 'nullable|array',
            'emotions.*' => 'exists:emotions,id',
            'intensity' => 'nullable|array',
            'intensity.*' => 'nullable|integer|min:1|max:10',
        ]);

        DB::transaction(function () use ($validated) {
            $entry = Auth::user()->diaryEntries()->create([
                'date' => $validated['date'],
                'content' => $validated['content'],
            ]);

            if (!empty($validated['emotions'])) {
                $attach = [];
                foreach ($validated['emotions'] as $emotionId) {
                    $attach[$emotionId] = [
                        'intensity' => $validated['intensity'][$emotionId] ?? null
                    ];
                }
                $entry->emotions()->attach($attach);
            }
        });

        return redirect()->route('diary.index')->with('status', 'Diary entry created.');
    }

    public function show(DiaryEntry $diary)
    {
        $this->authorize('view', $diary);
        $diary->load('emotions');
        return view('diary.show', ['diaryEntry' => $diary]);
    }

    public function edit(DiaryEntry $diary)
    {
        $this->authorize('update', $diary);
        $emotions = Emotion::all();
        $diary->load('emotions');
        return view('diary.edit', compact('diary', 'emotions'));
    }

    public function update(Request $request, DiaryEntry $diary)
    {
        $this->authorize('update', $diary);

        $validated = $request->validate([
            'date' => 'required|date',
            'content' => 'required|string',
            'emotions' => 'nullable|array',
            'emotions.*' => 'exists:emotions,id',
            'intensity' => 'nullable|array',
            'intensity.*' => 'nullable|integer|min:1|max:10',
        ]);

        DB::transaction(function () use ($validated, $diary) {
            $diary->update([
                'date' => $validated['date'],
                'content' => $validated['content'],
            ]);

            if (!empty($validated['emotions'])) {
                $sync = [];
                foreach ($validated['emotions'] as $emotionId) {
                    $sync[$emotionId] = ['intensity' => $validated['intensity'][$emotionId] ?? null];
                }
                $diary->emotions()->sync($sync);
            } else {
                $diary->emotions()->sync([]);
            }
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
