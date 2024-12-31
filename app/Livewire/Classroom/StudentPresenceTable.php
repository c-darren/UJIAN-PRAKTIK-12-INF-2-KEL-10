<?php

namespace App\Livewire\Classroom;

use Carbon\Carbon;
use Livewire\Component;
use App\Models\Classroom\ClassPresence;

class StudentPresenceTable extends Component
{
    public $masterClass_id;
    public $class_id;
    public $presences;

    public function mount($masterClass_id, $class_id, $classList)
    {
        $this->masterClass_id = $masterClass_id;
        $this->class_id = $class_id;
        
        $this->presences = ClassPresence::with(['attendance.topic'])
            ->whereHas('attendance', function($query) use ($class_id) {
                $query->where('class_id', $class_id);
            })
            ->where('user_id', auth()->id())
            ->get()
            ->map(function($presence) {
                return [
                    'id' => $presence->attendance_id,
                    'date' => $presence->attendance->attendance_date,
                    'topic' => $presence->attendance->topic->topic_name,
                    'topic_id' => $presence->attendance->topic_id,
                    'status' => $presence->status,
                ];
            })->toArray();
    }

    public function render()
    {
        return view('livewire.classroom.student-presence-table');
    }
}
