
@extends('layouts.app')

@section('content')
	<h1 class="m-b-0 app-logo-header">Welcome to Loggit!</h1>
	<h2 class="app-logo-subtitle m-t-0"><small>The simple solution to logging your workouts</small></h2>
	<hr class="fadeIn">
	<section class="row penAndPaper">
		<div class="col-sm-7 fadeIn">
			<h2>Tired of bringing pen and paper to the gym?</h2>
			<p class="lead">Why not log your workouts on your mobile deivce? Your phone is probably in your pocket playing music anyways!</p>
			<p>
				@if (Auth::guest())
					Why dont you give it a try? <a href="/register">Register right now and get going!</a> I'm a simple man. No need for email verification or information like age or sex. You'll be signed in and ready to go in literally seconds.
				@else
					Why dont you give it a try? <a class="pointer">Register rig...</a> Hey you're already signed in! Why the fuck are you still lurking on the front page? Get going, dude. <a href="/dashboard/my_routines/add_routine">Set up a workout session</a> or <a href="/dashboard/start">start a workout</a> and hit the gym. Goddammit, {{ Auth::user()->name }}!
				@endif
			</p>
		</div>
		<div class="col-sm-5">
			<div class="phone slideInLeft">
				<img class="viewPhone" src="/img/entire_phone.png">
				<!-- <img class="viewPhone_shadow" src="/img/phone_shadow.png"> -->
			</div>
		</div>
	</section>

	<h2 class="fadeIn">Why Loggit?</h2>
	<section class="row text-center">
		<div class="col-sm-4 slideInRight">
			<div class="panel">
				<h3><span class="fa fa-pencil"></span> Easy setup</h3>
				<p class="lead">Add youre sessions and youre ready to go.</p>
			</div>
		</div>
		<div class="col-sm-4 slideInBottom">
			<div class="panel">
				<h3><span class="fa fa-area-chart"></span> Useful statistics</h3>
				<p class="lead">Add youre sessions and youre ready to go.</p>
			</div>
		</div>
		<div class="col-sm-4 slideInLeft">
			<div class="panel">
				<h3><span class="fa fa-thumbs-o-up"></span> It's free</h3>
				<p class="lead">Yup. It's also open source.</p>
			</div>
		</div>
	</section>

	<h2 class="fadeIn">Core features</h2>
	<section class="row text-center">
		<div class="col-sm-3 fadeIn">
			<div class="panel">
				<h4><span class="fa fa-calendar-check-o"></span> Date specific statistics</h4>
				<p>Watch your progress as time passes. See when youre not as active as you might wish to be.</p>
			</div>
		</div>

		<div class="col-sm-3 fadeIn">
			<div class="panel">
				<h4><span class="fa fa-puzzle-piece"></span> Add custom Routines</h4>
				<p>Predifining your routine saves you a lot of typing on your phone when working out.</p>
			</div>
		</div>

		<div class="col-sm-3 fadeIn">
			<div class="panel">
				<h4><span class="fa fa-heartbeat"></span> Easy workout walkthrough</h4>
				<p>Focus on keeping your heartbeat up instead of spending your time entering data on your phone.</p>
			</div>
		</div>

		<div class="col-sm-3 fadeIn">
			<div class="panel">
				<h4><span class="fa fa-magic"></span> Tracks your body fat</h4>
				<p>No of course it doesn't you idiot. But I really wanted four panels in this row. Looks nice yeah?</p>
			</div>
		</div>
	</section>
@endsection

@section('script')
	<script>
		$(document).ready(function(){
			$(".phone").on('mouseover', function() {
				$(this).find('.viewPhone').stop().animate({
					bottom: '4px'
				},{
					speed: 250,
					easing: 'swing'
				});

				// $(this).find('.viewPhone_shadow').stop().animate({
				// 	bottom: '8px',
				// 	right: '278px',
				// 	width: '333px'
				// },{
				// 	speed: 250,
				// 	easing: 'swing'
				// })
			});

			$(".phone").on('mouseleave', function() {
				$(this).find('.viewPhone').stop().animate({
					bottom: '0px'
				},{
					speed: 250,
					easing: 'swing'
				});

				// $(this).find('.viewPhone_shadow').stop().animate({
				// 	bottom: '0px',
				// 	right: '257px',
				// 	width: '360px'
				// },{
				// 	speed: 250,
				// 	easing: 'swing'
				// })
			});
		});
	</script>
@endsection