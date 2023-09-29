<?php

use Illuminate\Support\Facades\Broadcast;

/*//////
|--------------------------------------------------------------------------
| Broadcast Channels
|--------------------------------------------------------------------------
|
| Here you may register all of the event broadcasting channels that your
| application supports. The given channel authorization callbacks are
| used to check if an authenticated user can listen to the channel.
|
*/

Broadcast::channel('App.Models.Student.{id}', function ($student, $id) {
    return (int) $student->id === (int) $id;
});

Broadcast::channel('App.Models.Parentt.{id}', function ($parentt, $id) {
    return (int) $parentt->id === (int) $id;
});
