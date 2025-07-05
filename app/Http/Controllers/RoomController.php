<?php

namespace App\Http\Controllers;

use App\Models\Room;
use App\Models\RoomPhoto;
use App\Models\RoomTransferRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class RoomController extends Controller
{
    public function create()
    {
        return view('landboard.room.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'type'           => 'required|string|max:100|unique:rooms,type,NULL,id,landboard_id,' . Auth::user()->landboard->id,
            'gender_type'    => 'required|in:male,female,mixed',
            'price'          => 'required|numeric|min:0',
            'room_quantity'  => 'required|integer|min:1',
            'photos.*'       => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'facilities'     => 'nullable|array',
            'facilities.*'   => 'nullable|string|max:100',
            'rules'          => 'nullable|array',
            'rules.*'        => 'nullable|string|max:100',
        ]);

        $landboardId = Auth::user()->landboard->id;

        DB::beginTransaction();
        try {
            $lastRoom = Room::where('landboard_id', $landboardId)
                            ->where('type', $validated['type'])
                            ->orderBy('id', 'desc')
                            ->first();

            $start = $lastRoom ? (int) filter_var($lastRoom->room_number, FILTER_SANITIZE_NUMBER_INT) + 1 : 1;

            for ($i = 0; $i < $validated['room_quantity']; $i++) {
                $roomNumber = $validated['type'] . '-' . ($start + $i);

                $room = Room::create([
                    'landboard_id' => $landboardId,
                    'type'         => $validated['type'],
                    'gender_type'  => $validated['gender_type'],
                    'price'        => $validated['price'],
                    'room_number'  => $roomNumber,
                    'status'       => 'available',
                ]);

                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $photo) {
                        $path = $photo->store('room_photos', 'public');
                        $room->photos()->create(['path' => $path]);
                    }
                }

                foreach ((array) $validated['facilities'] as $facilityName) {
                    if (trim($facilityName)) {
                        $room->facilities()->create(['name' => $facilityName]);
                    }
                }

                foreach ((array) $validated['rules'] as $ruleName) {
                    if (trim($ruleName)) {
                        $room->rules()->create(['name' => $ruleName]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('landboard.rooms.index')->with('success', 'Kamar berhasil ditambahkan.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menambahkan kamar: ' . $e->getMessage());
        }
    }
    
    public function index(Request $request)
    {
        $query = Room::with(['photos', 'facilities', 'rules'])
            ->where('landboard_id', Auth::user()->landboard->id);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'price_asc':
                    $query->orderBy('price', 'asc');
                    break;
                case 'price_desc':
                    $query->orderBy('price', 'desc');
                    break;
                case 'room_az':
                    $query->orderBy('room_number', 'asc');
                    break;
                case 'room_za':
                    $query->orderBy('room_number', 'desc');
                    break;
            }
        }

        $rooms = $query->get();

        return view('landboard.room.index', compact('rooms'));
    }

    public function show($id)
    {
        $room = Room::with(['photos', 'facilities', 'rules'])->findOrFail($id);
        return view('landboard.room.show', compact('room'));
    }

    public function edit($id)
    {
        $room = Room::with(['photos', 'facilities', 'rules'])->findOrFail($id);

        if ($room->landboard_id !== Auth::user()->landboard->id) {
            abort(403);
        }

        return view('landboard.room.edit', compact('room'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'type'            => 'required|string|max:100',
            'gender_type'     => 'required|in:male,female,mixed',
            'price'           => 'required|numeric|min:0',
            'photos.*'        => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'facilities'      => 'nullable|array',
            'facilities.*'    => 'nullable|string|max:100',
            'rules'           => 'nullable|array',
            'rules.*'         => 'nullable|string|max:100',
            'delete_photos'   => 'nullable|array',
            'delete_photos.*' => 'nullable|integer',
            'apply_all'       => 'nullable|boolean',
        ]);

        $room = Room::with(['photos', 'facilities', 'rules'])->findOrFail($id);

        if ($room->landboard_id !== Auth::user()->landboard->id) {
            abort(403);
        }

        $targetRooms = $request->apply_all ? Room::where('type', $room->type)->get() : collect([$room]);

        DB::beginTransaction();
        try {
            foreach ($targetRooms as $targetRoom) {
                $targetRoom->update([
                    'type'        => $validated['type'],
                    'gender_type' => $validated['gender_type'],
                    'price'       => $validated['price'],
                ]);

                $targetRoom->facilities()->delete();
                foreach ((array) $validated['facilities'] as $facilityName) {
                    if (trim($facilityName)) {
                        $targetRoom->facilities()->create(['name' => $facilityName]);
                    }
                }

                $targetRoom->rules()->delete();
                foreach ((array) $validated['rules'] as $ruleName) {
                    if (trim($ruleName)) {
                        $targetRoom->rules()->create(['name' => $ruleName]);
                    }
                }

                if (!$request->apply_all && $request->has('delete_photos')) {
                    foreach ($request->delete_photos as $photoId) {
                        $photo = $targetRoom->photos()->where('id', $photoId)->first();
                        if ($photo) {
                            $isUsedByOthers = RoomPhoto::where('path', $photo->path)
                                ->where('room_id', '!=', $targetRoom->id)
                                ->exists();

                            if (!$isUsedByOthers) {
                                Storage::disk('public')->delete($photo->path);
                            }

                            $photo->delete();
                        }
                    }
                }

                if ($request->hasFile('photos')) {
                    foreach ($request->file('photos') as $photoFile) {
                        $path = $photoFile->store('room_photos', 'public');
                        $targetRoom->photos()->create(['path' => $path]);
                    }
                }
            }

            DB::commit();
            return redirect()->route('landboard.rooms.index')->with('success', 'Data kamar berhasil diperbarui.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal memperbarui kamar: ' . $e->getMessage());
        }
    }

    public function duplicateForm($id)
    {
        $room = Room::with(['photos', 'facilities', 'rules'])->findOrFail($id);

        $lastRoom = Room::where('type', $room->type)
                        ->where('landboard_id', $room->landboard_id)
                        ->orderBy('room_number', 'desc')
                        ->first();

        $lastNumber = $lastRoom
            ? (int) filter_var($lastRoom->room_number, FILTER_SANITIZE_NUMBER_INT)
            : 0;

        return view('landboard.room.duplicate', compact('room', 'lastNumber'));
    }

    public function duplicate(Request $request, $id)
    {
        $validated = $request->validate([
            'room_quantity' => 'required|integer|min:1',
        ]);

        $sourceRoom = Room::with(['photos', 'facilities', 'rules'])->findOrFail($id);
        $landboardId = Auth::user()->landboard->id;

        DB::beginTransaction();
        try {
            $existingNumbers = Room::where('landboard_id', $landboardId)
                ->where('type', $sourceRoom->type)
                ->pluck('room_number')
                ->map(function ($number) use ($sourceRoom) {
                    return (int) str_replace(strtolower($sourceRoom->type) . '-', '', strtolower($number));
                })->toArray();

            $maxNumber = $existingNumbers ? max($existingNumbers) : 0;
            $newRooms = [];

            for ($i = 1; $i <= $validated['room_quantity']; $i++) {
                $newNumber = ++$maxNumber;
                $roomNumber = strtolower($sourceRoom->type) . '-' . $newNumber;

                $newRoom = Room::create([
                    'landboard_id' => $landboardId,
                    'type'         => $sourceRoom->type,
                    'gender_type'  => $sourceRoom->gender_type,
                    'price'        => $sourceRoom->price,
                    'room_number'  => $roomNumber,
                    'status'       => 'available',
                ]);

                foreach ($sourceRoom->facilities as $facility) {
                    $newRoom->facilities()->create(['name' => $facility->name]);
                }

                foreach ($sourceRoom->rules as $rule) {
                    $newRoom->rules()->create(['name' => $rule->name]);
                }

                foreach ($sourceRoom->photos as $photo) {
                    $newPath = 'room_photos/' . uniqid() . '_' . basename($photo->path);
                    Storage::disk('public')->copy($photo->path, $newPath);
                    $newRoom->photos()->create(['path' => $newPath]);
                }

                $newRooms[] = $newRoom;
            }

            DB::commit();
            return redirect()->route('landboard.rooms.index')->with('success', count($newRooms) . ' kamar berhasil diduplikasi.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal duplikasi kamar: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        $room = Room::with(['photos', 'facilities', 'rules', 'rentalHistories'])->findOrFail($id);

        if ($room->landboard_id !== Auth::user()->landboard->id) {
            abort(403);
        }

        $hasActiveRental = $room->rentalHistories()
            ->whereIn('status', ['active', 'upcoming'])
            ->exists();

        if ($hasActiveRental) {
            return redirect()->route('landboard.rooms.index')
                ->with('error', 'Kamar tidak bisa dihapus karena masih ada tenant yang menempati atau reservasi yang aktif.');
        }

        DB::beginTransaction();
        try {
            RoomTransferRequest::where('current_room_id', $room->id)->delete();
            RoomTransferRequest::where('current_room_id', $room->id)->delete();

            foreach ($room->photos as $photo) {
                $isUsedByOthers = \App\Models\RoomPhoto::where('path', $photo->path)
                    ->where('room_id', '!=', $room->id)
                    ->exists();

                if (!$isUsedByOthers) {
                    Storage::disk('public')->delete($photo->path);
                }
            }

            $room->photos()->delete();
            $room->facilities()->delete();
            $room->rules()->delete();

            $room->delete();

            DB::commit();
            return redirect()->route('landboard.rooms.index')->with('success', 'Kamar berhasil dihapus.');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Gagal menghapus kamar: ' . $e->getMessage());
        }
    }
}