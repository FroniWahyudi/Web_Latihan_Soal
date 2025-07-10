<?php

namespace App\View\Components\Dashboard;

use Closure;
use Illuminate\View\Component;
use Illuminate\Contracts\View\View;

class SubjectCard extends Component
{
    public $subject;

    public function __construct($subject)
    {
        $this->subject = $subject;
    }

    public function render(): View|Closure|string
    {
        return view('components.dashboard.subject-card');
    }
}
