<?php

namespace Logit\Http\Controllers;

use Logit\User;
use Logit\Friend;
use Logit\Routine;
use Logit\Settings;
use Logit\Notification;
use Logit\RoutineJunction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
	/**
     * Grab all friends connected to Authed user
     *
     * @return \Illuminate\Http\Response
     */
    public function viewFriends ()
    {
		$brukerinfo = Auth::user();
		$friends = Friend::where([
				['friends_with', Auth::id()],
				['pending', 0]
			])
			->join('users', 'friends.user_id', 'users.id')
			->select('friends.id', 'friends.user_id', 'friends.created_at', 'users.name', 'users.email')
			->get();

		// Finds people that are trying to be your friend
		$pending = Friend::where([
			['friends_with', Auth::id()],
			['pending', 1]
		])
		->join('users', 'friends.user_id', '=', 'users.id')
		->get();

		$topNav = [
            0 => [
                'url'  => '/dashboard/friends',
                'name' => 'Friends'
            ]
        ];

    	return view('friends.friends', [
    		'brukerinfo' => $brukerinfo,
    		'topNav'	 => $topNav,
    		'friends' 	 => $friends,
    		'pending' 	 => $pending,
		]);
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
    		abort(403, 'You need to be friends with the person to view this page');
    	}

    	$friend = User::where('id', $friendId)->first();

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
    		'brukerinfo' => $brukerinfo,
    		'routines'	 => $routines,
    		'topNav'	 => $topNav,
    		'friend' 	 => $friend,
    	]);
    }

    /**
     * Shared a routine with another user
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function shareRoutine (Request $request)
    {
		$routine = Routine::where('id', $request->routine)->firstOrFail();
		$friend = $request->friend;

		if (!Friend::where([['user_id', auth::id()], ['friends_with'], $friend])) {
			abort(403, "You are not friends with this person!");
		}
		
		// Make sure the user actually owns the routine!
		if (!$routine->user_id == Auth::id()) {
			abort(403, "You do now own this routine!");
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
		$notify->url = '/dashboard/my_routines';

		if ($notify->save()) {
			return back()->with('script_success', 'Routine successfully shared.');
		} else {
			return back()->with('script_danger', 'Something went wrong. Please try again.');
		}
    }

    /**
     * Grabs all people matching the request querrystring
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function findFriends (Request $request)
    {

    	if ($request->q) {
    		$search = $request->q . "%";
	    	
	    	$result = User::join('settings', 'users.id', '=', 'settings.user_id')
	    		->where([
	    			['settings.accept_friends', '=', 1],
	    			['users.id', '!=', Auth::id()],
	    			['name', 'LIKE', $search]
				])
	    		->orWhere([
	    			['settings.accept_friends', '=', 1],
	    			['users.id', '!=', Auth::id()],
	    			['email', 'LIKE', $search]
				])
	    		->select('name', 'email', 'users.id')
	    		->get();

	    	return response()->json(
	            array(
	            	'total' => $result->count(),
	                'users' => $result,
	                )
	            );
    	}

    	return response()->json(array('error' => 'Search string cannot be empty!'));
    }

    /**
     * Sends a friends request to specified user
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function sendrequest (Request $request)
    {
    	$id = $request->id;
		$name = User::where('id', $id)->select('name')->first();

    	$settings = Settings::where('user_id', $id)->first();
    	// Checks if this person actually wants to recieve friendrequests
		if ($settings->accept_friends === 1) {

			$friends = Friend::where([['user_id', Auth::id()], ['friends_with', $id]])->first();
			// Checks if we're not already friends
			if (!$friends) {

				$pending = Friend::where([
					['user_id', $id],
					['friends_with', Auth::id()],
					['pending', 1]
				])->first();

				if (!$pending) {
					$requester = Auth::user();
					
					$newFriend = new Friend;
					$newFriend->user_id = Auth::id();
					$newFriend->friends_with = $id;
					$newFriend->pending = 1;

					$notify = new Notification;
					$notify->user_id = $id;
					$notify->content = $requester->name . " has sent you a friend request!";
					$notify->icon = 'insert_emoticon';
					$notify->url = '/dashboard/friends';

					if ($newFriend->save() && $notify->save()) {
						return response()->json(array('success' => 'A request has been sent to ' . $name->name));
					}

					return response()->json(array('error' => 'Something went wrong. Please try again or contact an admin.'));

				}
				else {
					return response()->json(array('error' => 'There is already an active invite between you two.'));
				}
			}
			else {
				return response()->json(array('error' => 'You are either already friends with ' . $name->name . ', or an invite is pending.'));
			}
		} 
		else {
			return response()->json(array('error' => 'This user does not accept requests'));
		}
    }

    /**
     * Respond to a specific friendrequest
     *
     * @param  Request
     * @return \Illuminate\Http\Response
     */
    public function respondRequest (Request $request)
    {
    	$id = $request->id;
		$name = User::where('id', $id)->select('name')->first();

		$friends = Friend::where([
			['user_id', Auth::id()], 
			['friends_with', $id],
			['pending', 0]
		])->first();

		// Checks if we're not already friends
		if (!$friends) {

			$accepter = Auth::user();

			// Updates the current pending request
			$pendingRequest = Friend::where('user_id', $id)->first();
			$pendingRequest->pending = 0;
			$pendingRequest->update();
			
			// Creates a new entry for the user that accepts the invite
			$friendship = new Friend;
			$friendship->user_id = Auth::id();
			$friendship->friends_with = $id;
			$friendship->pending = 0;

			$notify = new Notification;
			$notify->user_id = $id;
			$notify->content = ucfirst($accepter->name) . " has accepted you as a friend. How nice!";
			$notify->icon = 'insert_emoticon';
			$notify->url = '/dashboard/friends';

			if ($friendship->save() && $notify->save()) {
				return response()->json(array('success' => 'You are now friends with ' . ucfirst($name->name)));
			}

			return response()->json(array('error' => 'Something went wrong. Please try again or contact an admin.'));

		}
		else {
			return response()->json(array('error' => 'You are already friends with ' . ucfirst($name->name)));
		}
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
    	if ($name = Friend::where('id', $id)->select('user_id')->first()) {

    		$name = User::where('id', $name->user_id)->first();
    		$id = $name->id;

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
					return response()->json(array('success' => 'You are no longer friends with ' . ucfirst($name->name)));
				}

				return response()->json(array('error' => 'Something went wrong. Please try again or contact an admin.'));

			}
			else {
				return response()->json(array('error' => "You can't remove " + ucfirst($name->name) + " because the person is not your friend."));		
			}
    	}

    	return response()->json(array('error' => "That user does not exist."));
    }
}
