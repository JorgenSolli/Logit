<?php
namespace Logit\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use DB;
use Logit\Note;
use Logit\Workout;
use Logit\Settings;
use Logit\Notification;
use Logit\RoutineJunction;
use Logit\WorkoutJunction;

class ApiController extends Controller
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
     * Flushes sessions connected to gymming and exercises
     *
     * @return Redirect with Response
     */
    public function flushSessions ()
    {
        session()->forget('exercises');
        session()->forget('gymming');
        return redirect('/dashboard/start/')->with('script_success', 'Workout successfully stopped');
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
}