# Loggit
A webapp for logging workout sessions with a sidedish of statistics

This document is by no means completed. It is still under active 'development' and will constantly change untill it's marked as complete.

## FuncSpec
### The menu
The menu will consist of several links to pages
* Dashboard
* My routines
* Start workout
* Edit workouts

#### Dashboard
A view of graps and diagrams.
Including
* Total weight lifted per session (graph)
* Average sessions per week (Bar)
* Musclegroups worked out (pie)

#### My Routines
This is the list of a set routines that each user will personally define.

#### Start workout
The user will be promted to select a routine. Each excersice will be shown (in order) and the user will be able to fill in the information after completing an excersice. Then proceed to the next.
The user will be able to save and exit the workout-session before all excersisec are completed if neccecary. 

#### Edit workouts
Made some mistakes during your workout-session? You can see all previous saved sessions here and edit as you please.

### The dashboard
The user will have access to statistics such as graphs and diagrams. The main purpose is to let the user get an overview of their progress through a manually specified range of date.

## TechSpec
### Developing
#### Backend 
* [PHP v5.7](https://secure.php.net/)
* [Laravel v5.4](https://laravel.com/)
* MySQL

#### Frontend
* [Twitter Bootstrap v3.3.7](https://getbootstrap.com/)
* [JavaScript](https://www.javascript.com/)
* [jQuery v3.1.1](https://jquery.com/)
* [ChartJs v2.0](http://www.chartjs.org/)

### The database
The database will be a MySQL database.
#### Tables
##### users
* id
* name
* email
* password
* Laravel table data*

##### routes
* id
* user_id
* route_name

##### routes_junctions
* route_id
* excercise_name
* goal_reps
* goal_sets
* goal_weight
* Laravel table data*

##### workouts
* id
* user_id
* Laravel table data*

##### workouts_junctions
* workout_id
* excercise_name
* reps
* set_nr
* Laravel table data*

*Includes the date
