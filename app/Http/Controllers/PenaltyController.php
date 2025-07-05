<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PenaltyController extends Controller
{
    public function settings()
    {
        $landboard = Auth::user()->landboard;

        return view('landboard.penalty.settings', compact('landboard'));
    }

    public function update(Request $request)
    {
        $landboard = Auth::user()->landboard;

        $rules = [
            'late_fee_amount'             => 'nullable|integer|min:0',
            'moveout_penalty_amount'      => 'nullable|integer|min:0',
            'room_change_penalty_amount'  => 'nullable|integer|min:0',
            'decision_days_before_end'    => 'required|integer|min:1|max:30', 
        ];

        if ($request->has('is_penalty_enabled')) {
            $rules['late_fee_days'] = 'required|integer|min:1';
        } else {
            $rules['late_fee_days'] = 'nullable|integer|min:0';
        }

        $validated = $request->validate($rules);

        $landboard->update([
            'is_penalty_enabled'          => $request->has('is_penalty_enabled'),
            'late_fee_amount'             => $validated['late_fee_amount'],
            'late_fee_days'               => $validated['late_fee_days'],
            'moveout_penalty_amount'      => $validated['moveout_penalty_amount'],
            'room_change_penalty_amount'  => $validated['room_change_penalty_amount'],
            'is_penalty_on_moveout'       => $request->has('is_penalty_on_moveout'),
            'is_penalty_on_room_change'   => $request->has('is_penalty_on_room_change'),
            'decision_days_before_end'    => $validated['decision_days_before_end'],
        ]);

        return back()->with('success', 'Pengaturan penalti berhasil diperbarui.');
    }
}
