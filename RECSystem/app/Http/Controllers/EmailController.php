<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function create()
    {
        $members = User::all(); // or use a separate Member model
        return view('emails.send', compact('members'));
    }

    public function send(Request $request)
    {
        $request->validate([
            'member_id' => 'required|string',
            'template' => 'required|string',
            'attachments.*' => 'file|max:10240', // Optional: 10MB per file
        ]);
    
        $members = $request->member_id === 'all'
            ? User::all()
            : User::where('id', $request->member_id)->get();
    
        foreach ($members as $member) {
            $data = [
                'user' => $member,
                'custom_message' => $request->custom_message,
            ];
    
            Mail::send("emails.templates.{$request->template}", $data, function ($message) use ($member, $request) {
                $message->to($member->email, $member->name)
                        ->subject(ucwords(str_replace('_', ' ', $request->template)));
    
                if ($request->hasFile('attachments')) {
                    foreach ($request->file('attachments') as $file) {
                        $message->attach($file->getRealPath(), [
                            'as' => $file->getClientOriginalName(),
                            'mime' => $file->getClientMimeType(),
                        ]);
                    }
                }
            });
        }
    
        return redirect()->back()->with('success', 'Emails sent successfully!');
    }


}