<?php

namespace Logit\Http\Controllers;

use Logit\User;
use Logit\Friend;
use Logit\Routine;
use Logit\Settings;
use Logit\Notification;
use Logit\LatestActivity;
use Logit\WorkoutJunction;
use Logit\RoutineJunction;
use Logit\Mail\ShareRoutine;
use Logit\Classes\LogitFunctions;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;

class FriendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('timezone');
    }

    /**
         * Grab all friends connected to Authed user
     * @param Int $friendId the friend to view
     * @return \Illuminate\Http\Response
     */
    public function viewFriend ($friendId)
    {
        $user = Auth::user();
        LogitFunctions::canView($friendId);

        $friend = User::where('id', $friendId)->first();
        $latestActivity = LatestActivity::where('user_id', $friendId)
            ->orderBy('created_at', 'DESC')
            ->first();

        $myRoutines = Routine::where('user_id', Auth::id())->get();
        $routines = Routine::where('user_id', $friendId)->get();

        $topNav = [
            0 => [
                'url'  => '/friends',
                'name' => 'Friends'
            ],
            1 => [
                'url'  => '/friends/friend/' . $friendId,
                'name' => $friend->name
            ]
        ];

        return view('friends.friend', [
            'user'           => $user,
            'latestActivity' => $latestActivity,
            'myRoutines'     => $myRoutines,
            'routines'       => $routines,
            'topNav'         => $topNav,
            'friend'         => $friend,
        ]);
    }

	/**
     * Removes a friend from your friendslist
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function removeFriend (Request $request)
    {
    	$id = $request->id;
        if ($user = User::where('id', $id)->first()) {
    		$id = $user->id;

            $youAndHim = Friend::where([
                ['user_id', Auth::id()],
                ['friends_with', $id],
                ['pending', 0]
            ])->first();

            $himAndYou = Friend::where([
                ['user_id', $id],
                ['friends_with', Auth::id()],
                ['pending', 0]
            ])->first();

			# Makes sure you are both friends with eachother.
			if ($youAndHim && $himAndYou) {
				if ($youAndHim->delete() && $himAndYou->delete()) {
					return response()->json(array('success' => 'You are no longer friends with ' . ucfirst($user->name)));
				}

				return response()->json(array('error' => 'Something went wrong. Please try again or contact an admin.'));

			}
			else {
				return response()->json(array('error' => "You can't remove " + ucfirst($user->name) + " because the person is not your friend."));
			}
    	}

    	return response()->json(array('error' => "That user does not exist."));
    }

    /**
     * Gets all exercises from user
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
	public function getExercises (Request $request)
	{
		$you = Auth::user();
		$friend = User::where('id', $request->friend_id)->firstOrFail();

		$exercises = [
			'you' => [],
			'friend' => []
		];

        $your_exercises = LogitFunctions::getExercises($request->type, $request->year, $request->month, true, false, Auth::id());

        array_push($exercises['you'], $your_exercises);

		$friend_exercises = LogitFunctions::getExercises($request->type, $request->year, $request->month, true, false, $friend->id);

        array_push($exercises['friend'], $friend_exercises);

        return $exercises;
	}

	/**
     * Gets session amount from friend and Authed user
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
	public function getSessionData (Request $request)
	{
		$type = $request->type;
		$month = $request->month;
		$year = $request->year;
		$userId = $request->user_id;

		$friendData = LogitFunctions::fetchSessionData($type, $month, $year, $userId, true);
		$yourData  = LogitFunctions::fetchSessionData($type, $month, $year, Auth::id(), true);

		$result = array(
            'labels' => [],
            'series' => [
            	'yours' => [],
            	'friends' => [],
            ],
            'meta' => [],
            'max' => 0,
            'stepSize' => 1,
        );

		$result['labels'] = $friendData['labels'];
		$result['series']['yours'] = $yourData['series'];
		$result['series']['friends'] = $friendData['series'];

        if ($request->type == "month") {
            foreach ($yourData['meta'] as $key => $metaData) {

                if ($metaData !== "") {
                    if ($friendData['meta'][$key] !== "") {
                        $friendData['meta'][$key] .= ", " . $metaData;
                    } else {
                        $friendData['meta'][$key] .= $metaData;
                    }
                }
            }
            $result['meta'] = $friendData['meta'];
        }

        return $result;
	}

	/**
     * Gets data for specific exercise
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
	public function getExerciseData (Request $request)
	{
		$type = $request->type;
		$month = $request->month;
		$year = $request->year;

		if ($request->user_id === 'auth') {
			$userId = Auth::id();
		} else {
			$userId = $request->user_id;
		}
		$result = LogitFunctions::fetchExerciseData($type, $month, $year, $request->exercise, $userId);
		return $result;
	}

    /**
     * Shared a routine with another user
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function shareRoutine (Request $request)
    {

        $routine = Routine::where('id', $request->routineId)->firstOrFail();
        $friend = $request->friend;

        LogitFunctions::canView($friend);
        
        // Make sure the user actually owns the routine!
        if (!$routine->user_id == Auth::id()) {
            return response()
                ->view('errors.custom', [
                    'error' => 'You do not own this routine!'],
                    403
            );
        }

        $junctions = RoutineJunction::where('routine_id', $routine->id)->get();

        $shareRoutine = $routine->replicate();
        $shareRoutine->user_id = $friend;
        $shareRoutine->sharer = Auth::id();
        $shareRoutine->pending = 1;
        $shareRoutine->save();

        $routineId = $shareRoutine->id;

        foreach ($junctions as $junction) {
            $shareRoutineJunction = $junction->replicate();
            $shareRoutineJunction->user_id = $friend;
            $shareRoutineJunction->routine_id = $routineId;
            $shareRoutineJunction->save();
        }

        $notify = new Notification;
        $notify->user_id = $friend;
        $notify->content = Auth::user()->name . " has shared a routine with you!";
        $notify->icon = 'accessibility';
        $notify->url = '/routines';

        Mail::to(User::where('id', $friend)->firstOrFail())
            ->send(new ShareRoutine(
                $shareRoutine, 
                Auth::user(),
                User::where('id', $friend)->firstOrFail())
            );

        if ($notify->save()) {
            return back()->with('script_success', 'Routine successfully shared.');
        } else {
            return back()->with('script_danger', 'Something went wrong. Please try again.');
        }
    }
}