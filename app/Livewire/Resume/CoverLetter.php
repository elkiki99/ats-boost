<?php

namespace App\Livewire\Resume;

use Livewire\Component;

class CoverLetter extends Component
{
    public function render()
    {
        return view('livewire.resume.cover-letter')->title('Cover Letter • ATS Boost');
    }
}
