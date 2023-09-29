<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ParentAttendanceEvent implements ShouldBroadcast
{//
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $parent;
    public $student;

    /**
     * Create a new event instance.
     */
    public function __construct($parent,$student)
    {
        $this->parent = $parent;
        $this->student = $student;

    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('ParentAttendanceChannel'),
        ];
    }

    public function broadcastAs()
    {
        return 'Attendance Notification For Parents';
    }

    public function broadcastWith()
    {
        return [
            'student name'=>$this->student->first_name,
            'message'=>'were absent today!'
        ];
    }
}
