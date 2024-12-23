<?php

namespace App\Livewire\Classroom;

use Livewire\Component;
use App\Models\Classroom\AssignmentComment;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class TeacherCommentAssignmentTable extends Component
{
    public $masterClass_id;
    public $classList_id;
    public $resource_id;
    public $newComment = '';
    public $comments = [];

    protected $rules = [
        'newComment' => 'required|string|max:500',
    ];

    public function mount($masterClass_id, $classList_id, $resource_id)
    {
        $this->masterClass_id = $masterClass_id;
        $this->classList_id = $classList_id;
        $this->resource_id = $resource_id;
        $this->loadComments();
    }

    public function loadComments()
    {
        $comments = AssignmentComment::where('assignment_id', $this->resource_id)
                        ->with('user')
                        ->orderBy('created_at', 'desc')
                        ->get();

        $this->comments = $comments->map(function ($comment) {
            return [
                'user_name' => $comment->user->name,
                'response' => $comment->response,
                'created_human' => Carbon::parse($comment->created_at)->diffForHumans(),
            ];
        })->toArray();
    }

    public function postComment()
    {
        $this->validate();

        AssignmentComment::create([
            'assignment_id' => $this->resource_id,
            'user_id' => Auth::id(),
            'response' => $this->newComment,
            'created_at' => now(),
        ]);

        $this->newComment = '';
        $this->loadComments();
        $this->dispatch('comment-posted');
    }

    public function updateData()
    {
        $this->loadComments();
    }

    public function render()
    {
        return view('livewire.classroom.teacher-comment-assignment-table');
    }
}