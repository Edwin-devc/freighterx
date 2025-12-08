<?php

namespace App\Http\Controllers;

use App\Models\Invitation;
use App\Models\User;
use App\Notifications\TeamInvitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\Rules\Password;

class InvitationController extends Controller
{
    /**
     * Display invitations for the authenticated tenant
     */
    public function index()
    {
        $invitations = auth()->user()->tenant->invitations()
            ->with('inviter')
            ->latest()
            ->get();

        return view('invitations.index', compact('invitations'));
    }

    /**
     * Store a new invitation
     */
    public function store(Request $request)
    {
        $request->validate([
            'email' => 'required|email|unique:users,email',
            'role' => 'required|in:admin,manager,user',
        ]);

        // Check if invitation already exists for this email in this tenant
        $existingInvitation = Invitation::where('tenant_id', auth()->user()->tenant_id)
            ->where('email', $request->email)
            ->whereNull('accepted_at')
            ->first();

        if ($existingInvitation) {
            return back()->with('error', 'An invitation has already been sent to this email.');
        }

        $invitation = Invitation::create([
            'tenant_id' => auth()->user()->tenant_id,
            'invited_by' => auth()->id(),
            'email' => $request->email,
            'role' => $request->role,
        ]);

        // Send email notification
        try {
            Notification::route('mail', $request->email)
                ->notify(new TeamInvitation($invitation));

            return back()->with('success', 'Invitation sent successfully! An email has been sent to ' . $request->email);
        } catch (\Exception $e) {
            // If email fails, still show success with copy link option
            return back()->with('success', 'Invitation created! Email delivery may be pending. You can copy the invitation link from the pending invitations table.');
        }
    }

    /**
     * Show the invitation acceptance form
     */
    public function show($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return view('invitations.expired');
        }

        if ($invitation->isAccepted()) {
            return redirect()->route('login')->with('error', 'This invitation has already been accepted.');
        }

        return view('invitations.accept', compact('invitation'));
    }

    /**
     * Accept the invitation and create user account
     */
    public function accept(Request $request, $token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        if ($invitation->isExpired()) {
            return redirect()->route('register')->with('error', 'This invitation has expired.');
        }

        if ($invitation->isAccepted()) {
            return redirect()->route('login')->with('error', 'This invitation has already been accepted.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        // Create user
        $user = User::create([
            'tenant_id' => $invitation->tenant_id,
            'name' => $request->name,
            'email' => $invitation->email,
            'password' => Hash::make($request->password),
            'role' => $invitation->role,
        ]);

        // Mark invitation as accepted
        $invitation->update([
            'accepted_at' => now(),
        ]);

        // Log the user in
        auth()->guard('web')->login($user);

        return redirect()->route('dashboard')->with('success', 'Welcome to the team!');
    }

    /**
     * Delete an invitation
     */
    public function destroy(Invitation $invitation)
    {
        // Ensure invitation belongs to the current tenant
        if ($invitation->tenant_id !== auth()->user()->tenant_id) {
            abort(403);
        }

        $invitation->delete();

        return back()->with('success', 'Invitation deleted successfully.');
    }
}
