<?php

namespace App\Http\Controllers;

use App\Models\Reminder;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Routing\Attributes\Middleware;

#[Middleware('auth')]
class ReminderController extends Controller
{
    public function index()
    {
        $reminders = Auth::user()
            ->reminders()              // ความสัมพันธ์ใน User model: hasMany(Reminder::class)
            ->with('tags')             // eager load
            ->latest('remind_at')
            ->get();

        return view('reminders.index', compact('reminders'));
    }

    public function create()
    {
        $tags = Tag::all();
        return view('reminders.create', compact('tags'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'notes'     => ['nullable', 'string'],
            'remind_at' => ['nullable', 'date'],
            'status'    => ['required', 'in:new,done'],
            'tags'      => ['nullable', 'array'],
            'tags.*'    => ['integer', 'exists:tags,id'],
        ]);

        $reminder = Auth::user()->reminders()->create([
            'title'     => $validated['title'],
            'notes'     => $validated['notes'] ?? null,
            'remind_at' => $validated['remind_at'] ?? null,
            'status'    => $validated['status'] ?? 'new',
        ]);

        $reminder->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('reminders.index')->with('status', 'Reminder created.');
    }

    public function show(Reminder $reminder)
    {
        $this->authorize('view', $reminder);
        $reminder->load('tags');

        return view('reminders.show', compact('reminder'));
    }

    public function edit(Reminder $reminder)
    {
        $this->authorize('update', $reminder);
        $reminder->load('tags');
        $tags = Tag::all();

        return view('reminders.edit', compact('reminder', 'tags'));
    }

    public function update(Request $request, Reminder $reminder)
    {
        $this->authorize('update', $reminder);

        $validated = $request->validate([
            'title'     => ['required', 'string', 'max:255'],
            'notes'     => ['nullable', 'string'],
            'remind_at' => ['nullable', 'date'],
            'status'    => ['required', 'in:new,done'],
            'tags'      => ['nullable', 'array'],
            'tags.*'    => ['integer', 'exists:tags,id'],
        ]);

        $reminder->update([
            'title'     => $validated['title'],
            'notes'     => $validated['notes'] ?? null,
            'remind_at' => $validated['remind_at'] ?? null,
            'status'    => $validated['status'] ?? 'new',
        ]);

        $reminder->tags()->sync($validated['tags'] ?? []);

        return redirect()->route('reminders.index')->with('status', 'Reminder updated.');
    }

    public function destroy(Reminder $reminder)
    {
        $this->authorize('delete', $reminder);
        $reminder->delete();

        return redirect()->route('reminders.index')->with('status', 'Reminder deleted.');
    }
}
