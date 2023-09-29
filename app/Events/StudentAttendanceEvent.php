<?php

namespace App\Events;

use App\Models\Student;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class StudentAttendanceEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
////////

    public $student;
    //public $parent;


    /**
     * Create a new event instance.
     */
    public function __construct($student)
    {
        $this->student = $student;
    }


    public function broadcastOn()
    {
        return new Channel('StudentAttendanceChannel');
    }

    public function broadcastAs()
    {
        return 'Attendance Notification For Students';
    }

    public function broadcastWith()
    {
        return [
            'student name'=>$this->student->first_name,
            'message'=>'were absent today!'
        ];
    }


}


