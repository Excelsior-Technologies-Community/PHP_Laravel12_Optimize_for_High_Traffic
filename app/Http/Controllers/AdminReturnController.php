<?php

namespace App\Http\Controllers;

use App\Models\ReturnRequest;
use Illuminate\Http\Request;

class AdminReturnController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->status;

        $returns = ReturnRequest::with(['order', 'customer', 'orderItem.product'])
            ->when($status, function ($query, $status) {
                $query->where('status', $status);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(15)
            ->withQueryString();

        return view('admin.returns.index', compact('returns', 'status'));
    }

    public function updateStatus(Request $request, ReturnRequest $returnRequest)
    {
        $request->validate([
            'status' => 'required|in:pending,approved,rejected,processed,completed',
            'admin_note' => 'nullable|string',
        ]);

        $returnRequest->update([
            'status' => $request->status,
            'admin_note' => $request->admin_note,
        ]);

        return back()->with('success', 'Return request status updated');
    }
}
