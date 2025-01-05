<?php
namespace App\Livewire\Classroom;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom\Assignment;
use App\Models\Classroom\ClassList;
use App\Models\Classroom\AssignmentSubmission;
use Illuminate\Support\Collection;

class SubmissionListComponent extends Component
{
    public $masterClassId;
    public $classListId;
    public $resourceId;
    public $openSections = [
        'submitted' => true,
        'returned' => false,
        'notSubmitted' => false
    ];
    public $selectedSubmissions = [];
    public $submissions;
    public $nonSubmittingStudents;
    public $lastPolledAt;
    public $assignment;

    protected $listeners = ['refreshSubmissions' => '$refresh'];

    public function mount($masterClassId, $classListId, $resourceId)
    {
        $this->masterClassId = $masterClassId;
        $this->classListId = $classListId;
        $this->resourceId = $resourceId;
        $this->lastPolledAt = now();
        
        // Initialize collections
        $this->submissions = collect();
        $this->nonSubmittingStudents = collect();
        
        $this->loadSubmissions();
    }

    public function loadSubmissions()
    {
        $this->assignment = Assignment::with(['topic'])
            ->where('class_id', $this->classListId)
            ->findOrFail($this->resourceId);

        // Fetch submissions
        $submissionsData = DB::table('assignment_submissions as s')
            ->join('users as u', 'u.id', '=', 's.user_id')
            ->select('s.*', 'u.name as student_name')
            ->where('s.assignment_id', $this->resourceId)
            ->whereIn('s.return_status', ['submitted', 'late', 'draft', 'scheduled', 'returned', 'mark as done'])
            ->get();

        // Convert to proper object collection
        $this->submissions = collect($submissionsData)->map(function($item) {
            $submission = new \stdClass;
            foreach ((array)$item as $key => $value) {
                $submission->$key = $value;
            }
            return $submission;
        });

        $nonSubmittingData = DB::table('class_students as cs')
        ->join('users as u', 'u.id', '=', 'cs.user_id')
        ->leftJoin('assignment_submissions as s', function($join) {
            $join->on('s.user_id', '=', 'cs.user_id')
                ->where('s.assignment_id', '=', $this->resourceId);
        })
        ->where('cs.class_id', $this->classListId)
        ->where(function($query) {
            $query->whereIn('s.return_status', ['assigned', 'progress'])
                  ->orWhereNull('s.return_status');
        })
        ->whereNotExists(function($query) {
            $query->select(DB::raw(1))
                ->from('assignment_submissions as s2')
                ->whereColumn('s2.user_id', 'cs.user_id')
                ->where('s2.assignment_id', $this->resourceId)
                ->whereNotIn('s2.return_status', ['assigned', 'progress']);
        })
        ->select('u.id', 'u.name')
        ->distinct()
        ->get();
        
        $this->nonSubmittingStudents = collect($nonSubmittingData)->map(function($item) {
            $student = new \stdClass;
            foreach ((array)$item as $key => $value) {
                $student->$key = $value;
            }
            return $student;
        });
        if (!$this->lastPolledAt || now()->diffInSeconds($this->lastPolledAt) >= 5) {

            $this->lastPolledAt = now();
        }
    }

    public function toggleSection($section)
    {
        $this->openSections[$section] = !$this->openSections[$section];
    }

    public function selectSubmission($submissionId)
    {
        if (in_array($submissionId, $this->selectedSubmissions)) {
            $this->selectedSubmissions = array_values(array_diff($this->selectedSubmissions, [$submissionId]));
        } else {
            $this->selectedSubmissions[] = $submissionId;
        }
        $this->dispatch('selection-changed', selectedSubmissions: $this->selectedSubmissions);
    }

    public function render()
    {
        return view('livewire.classroom.submission-list-component', [
            'assignment' => $this->assignment,
            'submissions' => $this->submissions,
            'nonSubmittingStudents' => $this->nonSubmittingStudents
        ]);
    }
}