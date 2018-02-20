<?php

namespace Logit\Http\Controllers;

use Logit\User;
use Logit\Friend;
use Logit\Routine;
use Logit\Settings;
use Logit\Notification;
use Logit\LatestActivity;
use Logit\WorkoutJunction;
use Logit\Classes\LogitFunctions;

use Carbon\Carbon;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendController extends Controller
{   
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('timezone');
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
     * Grab all friends connected to Authed user
     * @param Int $friendId the friend to view
     * @return \Illuminate\Http\Response
     */
    public function viewFriend ($friendId)
    {
    	$brukerinfo = Auth::user();

    	if (!Friend::where([ ['user_id', Auth::id()], ['friends_with', $friendId] ])->first()) {
            return response()
                ->view('errors.custom', [
                    'error' => 'You need to be friends with the person to view this page'],
                    403
            );
    	}

    	$friend = User::where('id', $friendId)->first();
        $latestActivity = LatestActivity::where('user_id', $friendId)
            ->orderBy('created_at', 'DESC')
            ->first();
            
    	$routines = Routine::where('user_id', Auth::id())->get();

    	$topNav = [
            0 => [
                'url'  => '/dashboard/friends/',
                'name' => 'Friends'
            ],
            1 => [
            	'url'  => '/dashboard/friends/friend/' + $friendId,
                'name' => $friend->name
            ]
        ];

    	return view('friends.friend', [
    		'brukerinfo'     => $brukerinfo,
            'latestActivity' => $latestActivity,
    		'routines'	     => $routines,
    		'topNav'	     => $topNav,
    		'friend' 	     => $friend,
    	]);
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

		$friendData = LogitFunctions::fetchSessionData($type, $month, $year, $userId);
		$yourData  = LogitFunctions::fetchSessionData($type, $month, $year, Auth::id());

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
}