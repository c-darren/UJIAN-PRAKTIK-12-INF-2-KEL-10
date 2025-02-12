<?php

namespace App\Http\Controllers\Classroom;

use Carbon\Carbon;
use App\Models\Auth\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Classroom\Topic;
use App\Models\Classroom\Material;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom\ClassList;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\Classroom\Assignment;
use App\Models\Classroom\ClassPresence;
use Illuminate\Support\Facades\Storage;
use App\Models\Classroom\ClassAttendance;
use App\Models\Classroom\AssignmentSubmission;
use Illuminate\Validation\ValidationException;
use App\ClassList\Student\Traits\AuthorizeAccess;

class StudentResourceController extends Controller
{
    use AuthorizeAccess;
    private static $lastCheck = null;

    public function __construct()
    {
        $masterClassId = request()->route('masterClass_id');
        $classId = request()->route('class_id');

        if ($masterClassId && $classId) {
            $this->checkScheduledSubmissions($masterClassId, $classId);
        }
    }

    private function needsWriteAccess(): bool
    {
        $routeName = request()->route()->getName();
        
        // Define routes that need write access
        $writeAccessRoutes = [
            'student.classroom.resources.submissions.store',
            'student.classroom.resources.submissions.complete',
            'student.classroom.resources.submissions.cancel',
            'student.classroom.resources.store-feedback',
            'student.classroom.resources.delete-feedback',
        ];

        return in_array($routeName, $writeAccessRoutes);
    }

    private function checkScheduledSubmissions($masterClass_id, $class_id)
    {
        $this->authorizeAccess(3, $masterClass_id, $class_id, $this->needsWriteAccess());
        
        $now = Carbon::now();
        if (!self::$lastCheck || $now->diffInSeconds(self::$lastCheck) >= 60) {
            try {
                DB::beginTransaction();
                AssignmentSubmission::where('return_status', 'scheduled')
                    ->where('scheduled_return_at', '<=', $now)
                    ->update([
                        'return_status' => 'returned',
                        'returned_at' => DB::raw('scheduled_return_at')
                    ]);
                DB::commit();
                self::$lastCheck = $now;
            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Failed to process scheduled submissions: ' . $e->getMessage());
            }
        }
    }

    public function index($masterClass_id, $class_id)
    {
        // Mendapatkan ClassList
        $classList = ClassList::findOrFail($class_id);
        
        // Mendapatkan Topics
        $topics = Topic::where('class_id', $class_id)->get();
        
        return $this->student_view('index', [
            'page_title' => 'Materials and Assignments',
            'masterClass_id' => $masterClass_id,
            'classList' => $classList,
            'topics' => $topics,
        ]);
    }

    public function all($masterClass_id, $class_id)
    {
        // Mendapatkan ClassList
        $classList = ClassList::findOrFail($class_id);
        
        // Get teachers
        $teachers = $classList->teachers()
            ->select('users.name')
            ->orderBy('users.name', request('sort', 'asc'))
            ->get();
    
        // Get enrolled students
        $students = $classList->students()
            ->select('users.name')
            ->orderBy('users.name', request('sort', 'asc'))
            ->when(true, function($query) {
                return $query->paginate(30);
            });
    
        return $this->student_view('all', [
            'page_title' => 'Orang di ' . $classList->class_name,
            'masterClass_id' => $masterClass_id,
            'classList' => $classList,
            'teachers' => $teachers,
            'students' => $students
        ]);
    }

    public function show($masterClass_id, $class_id, $type, $resource_id)
    {
        $classList = ClassList::findOrFail($class_id);
        $topics = Topic::select('id', 'topic_name')->get();
        if ($type === 'material') {
            $resource = Material::with(['topic', 'author', 'editor'])
                                ->where('class_id', $class_id)
                                ->where('start_date', '<=', now())
                                ->findOrFail($resource_id);
            
            $page_title = $resource->material_name;
            $resource_id = $resource->id;
            $resource_name = $resource->material_name;
            $topic = $resource->topic;
            $author = $resource->author ? $resource->author->name : '-';
            $editor = $resource->editor ? $resource->editor->name : '-';
            $description = $resource->description;
            $start_date = $resource->start_date;
            $formatted_start_date = Carbon::parse($start_date)->format('D, j M Y');
    
            $attachments = $resource->attachment ? json_decode($resource->attachment, true) : [];
            $attachment_file_names = $resource->attachment_file_name ? json_decode($resource->attachment_file_name, true) : [];

            $created_at = $resource->created_at;
            $created_at = Carbon::parse($created_at)->format('D, j M Y H:i:s');

            $updated_at = $resource->updated_at;
            if ($updated_at != null) {
                $updated_at = Carbon::parse($updated_at)->format('D, j M Y H:i:s');
            }else{
                $updated_at = '-';
            }

            return $this->student_view('show', [
                'type' => 'material',
                'page_title' => $page_title,
                'masterClass_id' => $masterClass_id,
                'classList' => $classList,
                'resource_id' => $resource_id,
                'resource_type' => 'material',
                'resource_name' => $resource_name,
                'topic_id' => $topic->id,
                'topic_name' => $topic->topic_name,
                'topics' => $topics,
                'author' => $author,
                'editor' => $editor,
                'description' => $description,
                'start_date' => $start_date,
                'formatted_start_date' => $formatted_start_date,
                'attachments' => $attachments,
                'attachment_file_names' => $attachment_file_names,

                'created_at' => $created_at,
                'updated_at' => $updated_at,
            ]);
    
        } elseif ($type === 'assignment') {
            $resource = Assignment::with(['topic', 'author', 'editor'])
                                  ->where('class_id', $class_id)
                                  ->where('start_date', '<=', now())
                                  ->findOrFail($resource_id);
            
            $page_title = $resource->assignment_name;
            $resource_id = $resource->id;
            $resource_name = $resource->assignment_name;
            $topic = $resource->topic;
            $author = $resource->author ? $resource->author->name : '-';
            $editor = $resource->editor ? $resource->editor->name : '-';
            $description = $resource->description;

            $start_date = $resource->start_date;
            $formatted_start_date = Carbon::parse($start_date)->format('D, j M Y H:i:s');
            $end_date = $resource->end_date;
            $formatted_end_date = Carbon::parse($end_date)->format('D, j M Y H:i:s');

            $accept_late_submissions = $resource->accept_late_submissions;
            $created_at = $resource->created_at;
            
            $created_at = Carbon::parse($created_at)->format('D, j M Y H:i:s');
            $updated_at = $resource->updated_at;
            if ($updated_at != null) {
                $updated_at = Carbon::parse($updated_at)->format('D, j M Y H:i:s');
            }else{
                $updated_at = '-';
            }
    
            $attachments = $resource->attachment ? json_decode($resource->attachment, true) : [];
            $attachment_file_names = $resource->attachment_file_name ? json_decode($resource->attachment_file_name, true) : [];
            $submission = AssignmentSubmission::where([
                'assignment_id' => $resource_id,
                'user_id' => auth()->id()
            ])->first();

            if($resource && !$submission) {
                $submission = new AssignmentSubmission();
                $submission->assignment_id = $resource_id;
                $submission->user_id = auth()->id();
                $submission->return_status = 'assigned';
                $submission->save();
            }
            
            $studentAttachments = [];
            if ($submission && $submission->attachment) {
                $paths = json_decode($submission->attachment, true);
                $names = json_decode($submission->attachment_file_name, true);
                
                foreach ($paths as $idx => $path) {
                    $studentAttachments[] = [
                        'path' => $path,
                        'fileName' => $names[$idx] ?? basename($path),
                        'fileType' => pathinfo($path, PATHINFO_EXTENSION),
                        'attachmentUrl' => route('student.classroom.resources.view-attachment', [
                            'masterClass_id' => $masterClass_id,
                            'class_id' => $class_id,
                            'type' => 'submission',
                            'resource_id' => $submission->id,
                            'attachment_index' => $idx
                        ]),
                        'downloadUrl' => route('student.classroom.resources.download-attachment', [
                            'masterClass_id' => $masterClass_id,
                            'class_id' => $class_id,
                            'type' => 'submission',
                            'resource_id' => $submission->id,
                            'attachment_index' => $idx
                        ])
                    ];
                }
            }
            
            $feedbackUsers = [];
            if($submission) {
                if ($submission->feedback) {
                    $feedbacks = json_decode($submission->feedback, true) ?? [];
                    $userIds = array_unique(array_column($feedbacks, 'user_id'));
                    
                    $feedbackUsers = User::whereIn('id', $userIds)
                        ->whereNull('deleted_at')
                        ->get()
                        ->keyBy('id');
                }
            }

            return $this->student_view('show', [
                'type' => 'assignment',
                'page_title' => $page_title,
                'masterClass_id' => $masterClass_id,
                'classList' => $classList,
                'resource_id' => $resource_id,
                'resource_type' => 'assignment',
                'resource_name' => $resource_name,
                'topic_id' => $topic->id,
                'topic_name' => $topic->topic_name,
                'topics' => $topics,
                'author' => $author,
                'editor' => $editor,
                'description' => $description,
                'start_date' => $start_date,
                'formatted_start_date' => $formatted_start_date,
                'end_date' => $end_date,
                'parsed_end_date' => Carbon::parse($end_date),
                'formatted_end_date' => $formatted_end_date,
                'accept_late_submissions' => $accept_late_submissions,
                'attachments' => $attachments,
                'attachment_file_names' => $attachment_file_names,

                'created_at' => $created_at,
                'updated_at' => $updated_at,

                'submission' => $submission,
                'studentAttachments' => $studentAttachments,
                'feedbackUsers' => $feedbackUsers
            ]);
    
        } else {
            abort(400, 'Invalid resource type');
        }
    }

    public function downloadAttachment($masterClass_id, $class_id, $type, $resource_id, $attachment_index)
    {    
        if ($type === 'material') {
            $resource = Material::findOrFail($resource_id);
            $attachments = json_decode($resource->attachment, true);
            if (!isset($attachments[$attachment_index])) {
                abort(404, 'Attachment not found.');
            }
            $attachment = $attachments[$attachment_index];
            $originalNames = $resource->attachment_file_name ? json_decode($resource->attachment_file_name, true) : [];
            $originalName = $originalNames[$attachment_index] ?? basename($attachment);
            // $attachmentFileName = basename($attachment);
    
        } elseif ($type === 'assignment') {
            $resource = Assignment::findOrFail($resource_id);
            $attachments = json_decode($resource->attachment, true);
            if (!isset($attachments[$attachment_index])) {
                abort(404, 'Attachment not found.');
            }
            $attachment = $attachments[$attachment_index];
            $originalNames = $resource->attachment_file_name ? json_decode($resource->attachment_file_name, true) : [];
            $originalName = $originalNames[$attachment_index] ?? basename($attachment);
            // $attachmentFileName = basename($attachment);
    
        } elseif ($type === 'submission') {
            $resource = AssignmentSubmission::findOrFail($resource_id);
            $attachments = json_decode($resource->attachment, true);
            if (!isset($attachments[$attachment_index])) {
                abort(404, 'Attachment not found.');
            }
            $attachment = $attachments[$attachment_index];
            $originalNames = $resource->attachment_file_name ? json_decode($resource->attachment_file_name, true) : [];
            $originalName = $originalNames[$attachment_index] ?? basename($attachment);
            // $attachmentFileName = basename($attachment);
    
        } else {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }
    
        return Storage::disk('private')->download($attachment, $originalName);
    }

    public function student_view($page_content='index', $data=[])
    {
        return view("dashboard.classroom.student.resource.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }
    public function viewAttachment($masterClass_id, $class_id, $type, $resource_id, $attachment_index)
    {
        switch ($type) {
            case 'material':
                $resource = Material::findOrFail($resource_id);
                break;
            case 'assignment':
                $resource = Assignment::findOrFail($resource_id);
                break;
            case 'submission':
                $resource = AssignmentSubmission::findOrFail($resource_id);
                break;
            default:
                abort(400, 'Invalid resource type.');
        }
    
        $attachments = $resource->attachment ? json_decode($resource->attachment, true) : [];
        if (!isset($attachments[$attachment_index])) {
            abort(404, 'Attachment not found.');
        }
    
        $path = $attachments[$attachment_index];
        $originalNames = $resource->attachment_file_name ? json_decode($resource->attachment_file_name, true) : [];
        $originalName = $originalNames[$attachment_index] ?? basename($path);
    
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'File not found.');
        }
    
        $mimeType = Storage::disk('private')->mimeType($path);
        return response()->file(Storage::disk('private')->path($path), [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . str_replace('"', '\\"', $originalName) . '"'
        ]);
    }

    public function storeFeedback(Request $request, $masterClass_id, $class_id, $submission_id)
    {
        try {
            $submission = AssignmentSubmission::findOrFail($submission_id);
            $user = auth()->user();
            
            $feedback = json_decode($submission->feedback, true) ?? [];
            $feedback[] = [
                'content' => $request->content,
                'timestamp' => now()->format('Y-m-d H:i:s'),
                'user_id' => $user->id,
            ];
            
            $submission->update(['feedback' => json_encode($feedback)]);
            
            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil disimpan'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan feedback',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function deleteFeedback($masterClass_id, $class_id, $submission_id, $index)
    {
        try {
            $submission = AssignmentSubmission::findOrFail($submission_id);
            
            // Ambil feedback yang ada
            $feedbacks = json_decode($submission->feedback, true) ?? [];
            
            // Validasi index dan kepemilikan feedback
            if (!isset($feedbacks[$index]) || $feedbacks[$index]['user_id'] !== auth()->id()) {
                throw new \Exception('Anda tidak memiliki akses untuk menghapus komentar ini');
            }
            
            // Hapus feedback pada index tersebut
            array_splice($feedbacks, $index, 1);
            
            // Update submission dengan feedback yang baru
            $submission->update([
                'feedback' => json_encode($feedbacks)
            ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Feedback berhasil dihapus'
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus feedback: ' . $e->getMessage()
            ], 500);
        }
    }

    private function validateAndGetStatus($assignment, $submission)
    {
        $now = Carbon::now();
        $endDate = Carbon::parse($assignment->end_date);
        
        if ($now->isAfter($endDate)) {
            if (!$assignment->accept_late_submissions) {
                throw ValidationException::withMessages([
                    'deadline' => 'Batas waktu pengumpulan telah berakhir'
                ]);
            }
            return 'late';
        }
        
        return $submission->exists ? 'submitted' : 'progress';
    }

    public function storeSubmission(Request $request, $masterClass_id, $class_id, $assignment_id)
    {
        try {
            $validated = $request->validate([
                'attachments.*' => 'nullable|file',
                'delete_attachments' => 'nullable|array',
                'delete_attachments.*' => 'string'
            ]);
    
            $assignment = Assignment::findOrFail($assignment_id);
            // Validate deadline and get status
            $status = $this->validateAndGetStatus($assignment, $submission ?? new AssignmentSubmission());

            // Get or create submission
            $submission = AssignmentSubmission::where([
                'assignment_id' => $assignment_id,
                'user_id' => auth()->id(),
            ])->first();
    
            // Get existing files
            $existingPaths = json_decode($submission->attachment ?? '[]', true);
            $existingNames = json_decode($submission->attachment_file_name ?? '[]', true);
    
            // Remove deleted files
            if ($request->has('delete_attachments')) {
                $deletePaths = $request->delete_attachments;
                foreach ($deletePaths as $deletePath) {
                    $index = array_search($deletePath, $existingPaths);
                    if ($index !== false) {
                        array_splice($existingPaths, $index, 1);
                        array_splice($existingNames, $index, 1);
                        
                        // Delete file from storage
                        Storage::disk('private')->delete($deletePath);
                    }
                }
            }
    
            // Add new files
            if ($request->hasFile('attachments')) {
                foreach ($request->file('attachments') as $file) {
                    $uuid = (string) Str::uuid();
                    $extension = $file->getClientOriginalExtension();
                    $encryptedName = $uuid . '.' . $extension;
                    
                    $path = $file->storeAs('submissions/attachments', $encryptedName, 'private');
                    
                    $existingPaths[] = $path;
                    $existingNames[] = $file->getClientOriginalName();
                }
            }
    
            // Update submission
            $submission->attachment = json_encode($existingPaths);
            $submission->attachment_file_name = json_encode($existingNames);
            $submission->return_status = $status;
            $submission->returned_at = now();
            $submission->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil dikirim'
            ]);
    
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal mengirim tugas: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function markAsComplete(Request $request, $masterClass_id, $class_id, $submission_id)
    {
        try {
            $submission = AssignmentSubmission::where([
                'assignment_id' => $submission_id,
                'user_id' => auth()->id()
            ])->first();

            if ($submission && json_decode($submission->attachment, true) !== []) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak dapat menandai sebagai selesai karena sudah ada file yang diunggah'
                ], 422);
            }

            // Create new submission or update existing one
            $submission = AssignmentSubmission::firstOrNew([
                'assignment_id' => $submission_id,
                'user_id' => auth()->id()
            ]);

            $assignment = Assignment::findOrFail($submission_id);
        
            // Validate deadline and get status
            $status = $this->validateAndGetStatus($assignment, $submission ?? new AssignmentSubmission());
            $submission->return_status = $status === 'late' ? 'late' : 'mark as done';
            $submission->attachment = [];
            $submission->save();

            return response()->json([
                'success' => true,
                'message' => 'Tugas berhasil ditandai sebagai selesai'
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menandai tugas sebagai selesai: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cancelSubmission(Request $request, $masterClass_id, $class_id, $submission_id)
    {
        $this->authorizeAccess(3, $masterClass_id, $class_id, true);

        try {
            $submission = AssignmentSubmission::with('assignment')
                ->where('id', $submission_id)
                ->where('user_id', auth()->id())
                ->firstOrFail();
            $assignment = Assignment::findOrFail($submission->assignment_id);

            $this->validateAndGetStatus($assignment, $submission ?? new AssignmentSubmission());
            $submission->return_status = 'progress';
            $submission->save();

            return response()->json([
                'success' => true,
                'message' => 'Pengumpulan tugas dibatalkan'
            ]);

        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $submission
                // 'message' => 'Gagal membatalkan pengumpulan: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function presence($masterClass_id, $class_id)
    {
        $classList = ClassList::with('topics')->findOrFail($class_id);

        return $this->student_view('presence', [
            'page_title' => $classList->class_name . ' - Presensi',
            'masterClass_id' => $masterClass_id,
            'classList' => $classList,
        ]); 
    }
}