<?php

namespace Logit\Http\Controllers;

use Logit\User;
use Logit\Friends;
use Logit\Settings;
use Logit\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FriendsController extends Controller
{
    public function viewFriends ()
    {
		$brukerinfo = Auth::user();
		$friends = Friends::where('user_id', Auth::id())
			->get();

		// Finds people that are trying to be your friend
		$pending = Friends::where([
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

    	return view('friends', [
    		'brukerinfo' => $brukerinfo,
    		'topNav'	 => $topNav,
    		'friends' 	 => $friends,
    		'pending' 	 => $pending,
		]);
    }

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

    public function sendrequest (Request $request)
    {
    	$id = $request->id;
		$name = User::where('id', $id)->select('name')->first();

    	$settings = Settings::where('user_id', $id)->first();
    	// Checks if this person actually wants to recieve friendrequests
		if ($settings->accept_friends === 1) {

			$friends = Friends::where([['user_id', Auth::id()], ['friends_with', $id]])->first();
			// Checks if we're not already friends
			if (!$friends) {

				$pending = Friends::where([
					['user_id', $id],
					['friends_with', Auth::id()],
					['pending', 1]
				])->first();

				if (!$pending) {
					$requester = Auth::user();
					
					$newFriend = new Friends;
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
				return response()->json(array('error' => 'You are already friends with ' . $name->name . ', or an invite is pending.'));
			}
		} 
		else {
			return response()->json(array('error' => 'User does not accept requests'));
		}
    }

    public function respondeRequest (Request $request)
    {
    	
    }
}
