<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Saung;
use App\Models\SaungSchedule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SaungController extends Controller
{
    public function index()
    {
        $saungs = Saung::withCount('reservations')->orderBy('name')->paginate(15);
        return view('admin.saungs.index', compact('saungs'));
    }

    public function create()
    {
        return view('admin.saungs.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('saungs', 'public');
        }

        Saung::create($data);

        return redirect()
            ->route('admin.saungs.index')
            ->with('success', 'Saung berhasil ditambahkan.');
    }

    public function edit(Saung $saung)
    {
        return view('admin.saungs.edit', compact('saung'));
    }

    public function update(Request $request, Saung $saung)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'capacity' => 'required|integer|min:1',
            'location' => 'nullable|string|max:255',
            'description' => 'nullable|string',
            'price_per_hour' => 'required|numeric|min:0',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->except('image');

        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($saung->image && Storage::disk('public')->exists($saung->image)) {
                Storage::disk('public')->delete($saung->image);
            }
            $data['image'] = $request->file('image')->store('saungs', 'public');
        }

        $saung->update($data);

        return redirect()
            ->route('admin.saungs.index')
            ->with('success', 'Saung berhasil diupdate.');
    }

    public function destroy(Saung $saung)
    {
        if ($saung->reservations()->exists()) {
            return back()->withErrors(['error' => 'Saung tidak bisa dihapus karena ada reservasi terkait.']);
        }

        $saung->delete();

        return redirect()
            ->route('admin.saungs.index')
            ->with('success', 'Saung berhasil dihapus.');
    }

    public function toggleStatus(Saung $saung)
    {
        $saung->update(['is_active' => !$saung->is_active]);

        return back()->with('success', 'Status saung berhasil diubah.');
    }

    /**
     * Manage saung schedules
     */
    public function schedules(Saung $saung)
    {
        $schedules = $saung->schedules()->orderByRaw("FIELD(day_of_week, 'monday', 'tuesday', 'wednesday', 'thursday', 'friday', 'saturday', 'sunday')")->get();
        
        return view('admin.saungs.schedules', compact('saung', 'schedules'));
    }

    public function storeSchedule(Request $request, Saung $saung)
    {
        $request->validate([
            'day_of_week' => 'required|in:monday,tuesday,wednesday,thursday,friday,saturday,sunday',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
        ]);

        // Check for overlapping schedules
        $exists = $saung->schedules()
            ->where('day_of_week', $request->day_of_week)
            ->where(function($query) use ($request) {
                $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                      ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
            })
            ->exists();

        if ($exists) {
            return back()->withErrors(['error' => 'Jadwal bentrok dengan jadwal yang sudah ada.']);
        }

        $saung->schedules()->create($request->all());

        return back()->with('success', 'Jadwal berhasil ditambahkan.');
    }

    public function deleteSchedule(Saung $saung, SaungSchedule $schedule)
    {
        if ($schedule->saung_id !== $saung->id) {
            abort(404);
        }

        $schedule->delete();

        return back()->with('success', 'Jadwal berhasil dihapus.');
    }

    public function toggleScheduleStatus(Saung $saung, SaungSchedule $schedule)
    {
        if ($schedule->saung_id !== $saung->id) {
            abort(404);
        }

        $schedule->update(['is_active' => !$schedule->is_active]);

        return back()->with('success', 'Status jadwal berhasil diubah.');
    }
}
