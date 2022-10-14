<?php

namespace App\View\Components;

use App\Models\Announcement;
use Illuminate\View\Component;

class AnnouncementsComponent extends Component
{
    public $itemCount;
    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct($itemCount = 5)
    {
        $this->itemCount = $itemCount;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        $announcements = Announcement::where('status', 1)->orderBy('updated_at', 'DESC')->paginate($this->itemCount);
        return view('components.announcements-component', compact('announcements'));
    }
}
