<?php

namespace App\Livewire\Classroom;

use Livewire\Component;
use App\Models\Classroom\AssignmentSubmission;
use App\Models\Auth\User;
use Illuminate\Support\Facades\Auth;

class PrivateCommentAssignmentTable extends Component
{
    public $masterClass_id;
    public $classList_id;
    public $resource_id;
    public $submission;
    public $feedbackUsers;

    public function mount($masterClass_id, $classList_id, $resource_id)
    {
        $this->masterClass_id = $masterClass_id;
        $this->classList_id = $classList_id;
        $this->resource_id = $resource_id;
        $this->loadFeedback();
    }

    public function loadFeedback()
    {
        $this->submission = AssignmentSubmission::where([
            'assignment_id' => $this->resource_id,
            'user_id' => Auth::id()
        ])
        ->select('id', 'feedback')
        ->first();

        if ($this->submission && $this->submission->feedback) {
            $feedbacks = json_decode($this->submission->feedback, true) ?? [];
            $userIds = array_unique(array_column($feedbacks, 'user_id'));
            
            $this->feedbackUsers = User::whereIn('id', $userIds)
                ->whereNull('deleted_at')
                ->get()
                ->keyBy('id');
        }
    }

    public function render()
    {
        // Add polling every 10 seconds
        $this->dispatch('poll');
        return view('livewire.classroom.private-comment-assignment-table');
    }
}