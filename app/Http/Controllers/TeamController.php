<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class TeamController extends Controller
{
    /**
     * Display team members for the authenticated tenant
     */
    public function index()
    {
        $users = auth()->user()->tenant->users()
            ->orderBy('role')
            ->orderBy('name')
            ->get();

        $invitations = auth()->user()->tenant->invitations()
            ->whereNull('accepted_at')
            ->where('expires_at', '>', now())
            ->with('inviter')
            ->latest()
            ->get();

        return view('team.index', compact('users', 'invitations'));
    }

    /**
     * Update team member role
     */
    public function updateRole(Request $request, User $user)
    {
        // Ensure user belongs to the current tenant
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // Ensure current user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only admins can update user roles.');
        }

        // Don't allow changing own role
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot change your own role.');
        }

        $request->validate([
            'role' => 'required|in:admin,manager,user',
        ]);

        $user->update([
            'role' => $request->role,
        ]);

        return back()->with('success', 'User role updated successfully.');
    }

    /**
     * Remove team member
     */
    public function destroy(User $user)
    {
        // Ensure user belongs to the current tenant
        if ($user->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        // Ensure current user is admin
        if (auth()->user()->role !== 'admin') {
            abort(403, 'Only admins can remove users.');
        }

        // Don't allow deleting own account
        if ($user->id === auth()->id()) {
            return back()->with('error', 'You cannot delete your own account.');
        }

        $user->delete();

        return back()->with('success', 'User removed successfully.');
    }
}
