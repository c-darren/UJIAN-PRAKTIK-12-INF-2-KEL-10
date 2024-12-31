<div class="container mx-auto py-6">
    @livewire('classroom.student-presence-table', [
        'masterClass_id' => $masterClass_id,
        'class_id' => $classList->id,
        'classList' => $classList
    ])
</div>