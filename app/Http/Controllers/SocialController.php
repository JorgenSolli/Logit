<?php

namespace Logit\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use Logit\NewMessage;
use Logit\Notification;

class SocialController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('timezone');
    }
    
    /**
     * Gets the notifications for Authed user
     *
     * @return \Illuminate\Http\Response
     */
    public function checkNotifications ()
    {
        $notifications = Notification::where([
                ['user_id', Auth::id()],
                ['read', 0]
            ])
            ->select('id', 'user_id', 'content', 'url', 'icon', 'created_at')
            ->get();
        return response()->json(array('notifications' => $notifications));   
    }

    /**
     * Cleares notifications for Authed user
     *
     * @return Void
     */
    public function clearNotification (Request $request)
    {

        $notification = Notification::where([
                ['user_id', Auth::id()],
                ['id', $request->id]
            ])
            ->first();
        
        // If the notification belongs to this user
        if ($notification) {
            $notification->read = 1;
            $notification->save();
        }

        return;
    }

    /**
     * Markes a message as read
     *
     * @return Void
     */
    public function clearMessage (Request $request)
    {
        $messageId = $request->message_id;
        $message = NewMessage::where('id', $messageId)->first();

        if ($message->user_id == Auth::id()) {
            $message->is_new = 0;
            $message->save();

            return;
        }

        return response()
            ->view('errors.custom', [
                'error' => 'Not your message!'],
                403
        );
    }
}
