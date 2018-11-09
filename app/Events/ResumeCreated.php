<?php

namespace App\Events;

use App\Resume;
use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

/**
 * @package Resume Builder
 * @author  Abhishek Prakash <prakashabhishek6262@gmail.com>
 */
class ResumeCreated
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * The instance of the created resume.
     * 
     * @var Resume
     */
    public $resume;

    /**
     * Create a new event instance.
     *
     * @param  Resume $resume
     * 
     * @return void
     */
    public function __construct(Resume $resume)
    {
        $this->resume = $resume;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }
}
