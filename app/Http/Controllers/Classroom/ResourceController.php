<?php

namespace App\Http\Controllers\Classroom;

use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Classroom\Topic;
use App\Models\Classroom\Material;
use Illuminate\Support\Facades\DB;
use App\Models\Classroom\ClassList;
use App\Http\Controllers\Controller;
use App\Models\Classroom\Assignment;
use Illuminate\Support\Facades\Storage;
use App\Models\Classroom\AssignmentSubmission;
use App\Classroom\Traits\AuthorizesClassAccess;

class ResourceController extends Controller
{
    use AuthorizesClassAccess;

    public function index($masterClass_id, $class_id)
    {
        // Otorisasi akses
        $this->authorizeAccess(2, $masterClass_id, $class_id, false);
        
        // Mendapatkan ClassList
        $classList = ClassList::findOrFail($class_id);
        
        // Mendapatkan Topics
        $topics = Topic::where('class_id', $class_id)->get();
        
        return $this->teacher_view('index', [
            'page_title' => 'Materials and Assignments',
            'masterClass_id' => $masterClass_id,
            'classList' => $classList,
            'topics' => $topics,
        ]);
    }

    public function all($masterClass_id, $class_id)
    {
        // Otorisasi akses
        $this->authorizeAccess(2, $masterClass_id, $class_id, false);
        
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
    
        return $this->teacher_view('all', [
            'page_title' => 'Daftar Peserta Didik - ' . $classList->class_name,
            'masterClass_id' => $masterClass_id,
            'classList' => $classList,
            'teachers' => $teachers,
            'students' => $students
        ]);
    }

    public function store(Request $request, $masterClass_id, $class_id, $type)
    {
        // Otorisasi akses (sesuaikan method authorizeAccess Anda)
        $this->authorizeAccess(2, $masterClass_id, $class_id, true);
    
        if ($type === 'material') {
            $validated = $request->validate([
                'topic_id' => 'required|exists:topics,id',
                'material_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date|after_or_equal:today',
                'attachment.*' => 'nullable|file',
                'attachment_file_name' => 'nullable|array',
                'attachment_file_name.*' => 'nullable|string|max:255',
            ]);
    
            $material = new Material();
            $material->class_id = $class_id;
            $material->topic_id = $validated['topic_id'];
            $material->material_name = $validated['material_name'];
            $material->author_id = auth()->id();
            $material->description = $validated['description'] ?? null;
            $material->start_date = $validated['start_date'];
            $material->updated_at = null;
    
            $paths = [];
            $originalNames = [];
    
            if ($request->hasFile('attachment')) {
                $files = $request->file('attachment');
                $originalNamesInput = $validated['attachment_file_name'] ?? [];
    
                foreach ($files as $index => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $uniqueName = (string) Str::uuid() . '.' . $extension;
                    $paths[] = $file->storeAs('materials/attachments', $uniqueName, 'private');
    
                    $originalName = $originalNamesInput[$index] ?? $file->getClientOriginalName();
                    $originalNames[] = $originalName;
                }
    
                $material->attachment = json_encode($paths);
                $material->attachment_file_name = json_encode($originalNames);
            }
    
            $material->save();
            return response()->json(['success' => true, 'message' => 'Berhasil menambahkan materi'], 201);
    
        } elseif ($type === 'assignment') {
            $validated = $request->validate([
                'topic_id' => 'required|exists:topics,id',
                'assignment_name' => 'required|string|max:255',
                'description' => 'nullable|string',
                'start_date' => 'required|date|after_or_equal:today',
                'end_date' => 'required|date|after_or_equal:start_date',
                'accept_late_submissions' => 'in:true,false',
                'attachment.*' => 'nullable|file',
                'attachment_file_name' => 'nullable|array',
                'attachment_file_name.*' => 'nullable|string|max:255',
            ]);
    
            $assignment = new Assignment();
            $assignment->class_id = $class_id;
            $assignment->topic_id = $validated['topic_id'];
            $assignment->assignment_name = $validated['assignment_name'];
            $assignment->author_id = auth()->id();
            $assignment->description = $validated['description'] ?? null;
            $assignment->start_date = $validated['start_date'];
            $assignment->end_date = $validated['end_date'];
            $assignment->accept_late_submissions = $request->boolean('accept_late_submissions');
            $assignment->updated_at = null;
    
            $paths = [];
            $originalNames = [];
    
            if ($request->hasFile('attachment')) {
                $files = $request->file('attachment');
                $originalNamesInput = $validated['attachment_file_name'] ?? [];
    
                foreach ($files as $index => $file) {
                    $extension = $file->getClientOriginalExtension();
                    $uniqueName = (string) Str::uuid() . '.' . $extension;
                    $paths[] = $file->storeAs('assignments/attachments', $uniqueName, 'private');
    
                    $originalName = $originalNamesInput[$index] ?? $file->getClientOriginalName();
                    $originalNames[] = $originalName;
                }
    
                $assignment->attachment = json_encode($paths);
                $assignment->attachment_file_name = json_encode($originalNames);
            }
    
            $assignment->save();
            return response()->json(['success' => true, 'message' => 'Berhasil menambahkan tugas'], 201);
        } else {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }
    }

    public function show($masterClass_id, $class_id, $type, $resource_id)
    {        
        // Otorisasi akses
        $this->authorizeAccess(2, $masterClass_id, $class_id, false);
        $classList = ClassList::findOrFail($class_id);
        $topics = Topic::select('id', 'topic_name')->get();
        if ($type === 'material') {
            $resource = Material::with(['topic', 'author', 'editor'])
                                ->where('class_id', $class_id)
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

            return $this->teacher_view('show', [
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
    
            return $this->teacher_view('show', [
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
            ]);
    
        } else {
            abort(400, 'Invalid resource type');
        }
    }

    public function update(Request $request, $masterClass_id, $class_id, $type, $resource_id)
    {
        $this->authorizeAccess(2, $masterClass_id, $class_id, true);

        if ($type === 'material') {
            $resource = Material::where('class_id', $class_id)
                                ->where('id', $resource_id)
                                ->firstOrFail();

            $validated = $request->validate([
                'topic_id'                 => 'required|exists:topics,id',
                'material_name'            => 'required|string|max:255',
                'description'              => 'nullable|string',
                'start_date'               => 'required|date',
                'attachment.*'             => 'nullable|file',
                // 'attachment.*'             => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
                'delete_attachments'       => 'nullable|array',
                'delete_attachments.*'     => 'nullable|string', // Path unik yang disimpan di DB
            ]);

            $resource->topic_id       = $validated['topic_id'];
            $resource->material_name  = $validated['material_name'];
            $resource->description    = $validated['description'] ?? null;
            $resource->start_date     = $validated['start_date'];
            $resource->editor_id      = auth()->id();
            $resource->updated_at     = now();

            // 1. Hapus lampiran yang dihapus
            if ($request->has('delete_attachments')) {
                $deletedPaths = $request->input('delete_attachments', []);
                // Cek dan hapus file di storage
                foreach ($deletedPaths as $path) {
                    if (Storage::disk('private')->exists($path)) {
                        Storage::disk('private')->delete($path);
                    }
                }
                // Update array lampiran & nama file
                $existingPaths = json_decode($resource->attachment, true) ?? [];
                $existingNames = json_decode($resource->attachment_file_name, true) ?? [];
                $remainPaths = [];
                $remainNames = [];

                // Looping berdasarkan index array
                foreach ($existingPaths as $idx => $storedPath) {
                    if (!in_array($storedPath, $deletedPaths)) {
                        $remainPaths[] = $storedPath;
                        // Nama file asli juga harus ikut
                        $remainNames[] = $existingNames[$idx] ?? basename($storedPath);
                    }
                }

                $resource->attachment           = json_encode($remainPaths);
                $resource->attachment_file_name = json_encode($remainNames);
            }

            // 2. Tambah lampiran baru
            if ($request->hasFile('attachment')) {
                $existingPaths = json_decode($resource->attachment, true) ?? [];
                $existingNames = json_decode($resource->attachment_file_name, true) ?? [];

                foreach ($request->file('attachment') as $file) {
                    // Buat nama unik di storage
                    $uniqueName = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $storedPath = $file->storeAs('materials/attachments', $uniqueName, 'private');

                    $existingPaths[] = $storedPath;
                    // Simpan nama file asli
                    $originalName = $file->getClientOriginalName();
                    $existingNames[] = $originalName;
                }

                $resource->attachment           = json_encode($existingPaths);
                $resource->attachment_file_name = json_encode($existingNames);
            }

            $resource->save();
            $resource->load(['topic', 'author', 'editor']);

            return response()->json(['resource' => $resource, 'type' => 'material'], 200);
        }
        elseif ($type === 'assignment') {
            $resource = Assignment::where('class_id', $class_id)
                                  ->where('id', $resource_id)
                                  ->firstOrFail();

            $validated = $request->validate([
                'topic_id'                => 'required|exists:topics,id',
                'assignment_name'         => 'required|string|max:255',
                'description'             => 'nullable|string',
                'start_date'              => 'required|date',
                'end_date'                => 'required|date|after_or_equal:start_date',
                'accept_late_submissions' => 'boolean',
                'attachment.*'            => 'nullable|file',
                // 'attachment.*'            => 'nullable|file|mimes:pdf,doc,docx,jpg,png',
                'delete_attachments'      => 'nullable|array',
                'delete_attachments.*'    => 'nullable|string',
            ]);

            $resource->topic_id                = $validated['topic_id'];
            $resource->assignment_name         = $validated['assignment_name'];
            $resource->description             = $validated['description'] ?? null;
            $resource->start_date              = $validated['start_date'];
            $resource->end_date                = $validated['end_date'];
            $resource->accept_late_submissions = $validated['accept_late_submissions'] ?? false;
            $resource->editor_id               = auth()->id();
            $resource->updated_at              = now();

            // 1. Hapus lampiran yang dihapus
            if ($request->has('delete_attachments')) {
                $deletedPaths = $request->input('delete_attachments', []);
                foreach ($deletedPaths as $path) {
                    if (Storage::disk('private')->exists($path)) {
                        Storage::disk('private')->delete($path);
                    }
                }
                $existingPaths = json_decode($resource->attachment, true) ?? [];
                $existingNames = json_decode($resource->attachment_file_name, true) ?? [];
                $remainPaths = [];
                $remainNames = [];

                foreach ($existingPaths as $idx => $storedPath) {
                    if (!in_array($storedPath, $deletedPaths)) {
                        $remainPaths[] = $storedPath;
                        $remainNames[] = $existingNames[$idx] ?? basename($storedPath);
                    }
                }

                $resource->attachment           = json_encode($remainPaths);
                $resource->attachment_file_name = json_encode($remainNames);
            }

            // 2. Tambah lampiran baru
            if ($request->hasFile('attachment')) {
                $existingPaths = json_decode($resource->attachment, true) ?? [];
                $existingNames = json_decode($resource->attachment_file_name, true) ?? [];

                foreach ($request->file('attachment') as $file) {
                    $uniqueName = (string) Str::uuid() . '.' . $file->getClientOriginalExtension();
                    $storedPath = $file->storeAs('assignments/attachments', $uniqueName, 'private');
                    
                    $existingPaths[] = $storedPath;
                    $originalName = $file->getClientOriginalName();
                    $existingNames[] = $originalName;
                }

                $resource->attachment           = json_encode($existingPaths);
                $resource->attachment_file_name = json_encode($existingNames);
            }

            $resource->save();
            $resource->load(['topic', 'author', 'editor']);

            return response()->json(['resource' => $resource, 'type' => 'assignment'], 200);
        }
        else {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }
    }
    
    public function destroy($masterClass_id, $class_id, $type, $resource_id)
    {
        // Otorisasi akses
        $this->authorizeAccess(2, $masterClass_id, $class_id, true);
    
        if ($type === 'material') {
            // Temukan material berdasarkan $resource_id saja
            $resource = Material::findOrFail($resource_id);
            
            // Hapus lampiran jika ada
            if ($resource->attachment) {
                $attachments = json_decode($resource->attachment, true);
                foreach ($attachments as $path) {
                    Storage::disk('private')->delete($path);
                }
            }
            
            // Hapus material
            $resource->delete();
            
            return response()->json(['success' => true, 'message' => 'Material deleted successfully'], 200);
    
        } elseif ($type === 'assignment') {
            // Temukan assignment berdasarkan $resource_id saja
            $resource = Assignment::findOrFail( $resource_id);
    
            // Hapus lampiran jika ada
            if ($resource->attachment) {
                $attachments = json_decode($resource->attachment, true);
                foreach ($attachments as $path) {
                    Storage::disk('private')->delete($path);
                }
            }
    
            // Hapus assignment
            $resource->delete();
    
            return response()->json(['success' => true, 'message' => 'Assignment deleted successfully'], 200);
        } else {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }
    }

    public function downloadAttachment($masterClass_id, $class_id, $type, $resource_id, $attachment_index)
    {
        // Otorisasi akses
        $this->authorizeAccess(2, $masterClass_id, $class_id, false);
    
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
    
        } else {
            return response()->json(['error' => 'Invalid resource type'], 400);
        }
    
        return Storage::disk('private')->download($attachment, $originalName);
    }

    public function teacher_view($page_content='index', $data=[])
    {
        return view("dashboard.classroom.class_list.resource.main_view", array_merge([
            'page_content' => $page_content,
        ], $data));
    }
    public function viewAttachment($masterClass_id, $class_id, $type, $resource_id, $attachment_index)
    {
        // Otorisasi akses
        $this->authorizeAccess(2, $masterClass_id, $class_id, false);
    
        if ($type === 'material') {
            $resource = Material::where('class_id', $class_id)
                                ->findOrFail($resource_id);
            $attachments = $resource->attachment ? json_decode($resource->attachment, true) : [];
            if (!isset($attachments[$attachment_index])) {
                abort(404, 'Attachment not found.');
            }
            $path = $attachments[$attachment_index];
            $originalNames = $resource->attachment_file_name ? json_decode($resource->attachment_file_name, true) : [];
            $originalName = $originalNames[$attachment_index] ?? basename($path);
        } elseif ($type === 'assignment') {
            $resource = Assignment::where('class_id', $class_id)
                                  ->findOrFail($resource_id);
            $attachments = $resource->attachment ? json_decode($resource->attachment, true) : [];
            if (!isset($attachments[$attachment_index])) {
                abort(404, 'Attachment not found.');
            }
            $path = $attachments[$attachment_index];
            $originalNames = $resource->attachment_file_name ? json_decode($resource->attachment_file_name, true) : [];
            // $originalName = isset($originalNames[$attachment_index]) ? $originalNames[$attachment_index] : basename($path);
            $originalName = $originalNames[$attachment_index] ?? basename($path);
        } else {
            abort(400, 'Invalid resource type.');
        }
    
        // Check if file exists
        if (!Storage::disk('private')->exists($path)) {
            abort(404, 'File not found.');
        }
    
        // Get file MIME type
        $mimeType = Storage::disk('private')->mimeType($path);
        // Serve file with headers to display inline
        return response()->file(Storage::disk('private')->path($path), [
            'Content-Type' => $mimeType,
            'Content-Disposition' => 'inline; filename="' . str_replace('"', '\\"', $originalName) . '"'
        ]);
    }

    public function submissions($masterClass_id, $class_id, $type, $resource_id)
    {
        // Authorize access
        $this->authorizeAccess(2, $masterClass_id, $class_id, false);

        if ($type !== 'assignment') {
            abort(400, 'Invalid resource type');
        }

        $assignment = Assignment::with(['topic'])
            ->where('class_id', $class_id)
            ->findOrFail($resource_id);

        $submissions = DB::table('assignment_submissions as s')
            ->join('users as u', 'u.id', '=', 's.user_id')
            ->select('s.*', 'u.name as student_name')
            ->where('s.assignment_id', $resource_id)
            ->get();

        return $this->teacher_view('submissions', [
            'type' => 'assignment',
            'page_title' => 'Grade Submissions - ' . $assignment->assignment_name,
            'masterClass_id' => $masterClass_id,
            'classList_id' => $class_id,
            'assignment' => $assignment,
            'submissions' => $submissions
        ]);
    }

    public function gradeSubmission(Request $request, $masterClass_id, $class_id, $submission_id) 
    {
        $this->authorizeAccess(2, $masterClass_id, $class_id, true);

        $validated = $request->validate([
            'score' => 'required|numeric|min:0|max:100',
            'feedback' => 'nullable|string',
            'return_status' => 'required|in:draft,scheduled,returned',
            'scheduled_return_at' => 'required_if:return_status,scheduled|nullable|date|after:now'
        ]);

        $submission = AssignmentSubmission::findOrFail($submission_id);

        DB::transaction(function() use ($submission, $validated, $request) {
            $submission->score = $validated['score'];
            $submission->feedback = $validated['feedback'];
            $submission->assessed_by = auth()->id();
            $submission->return_status = $validated['return_status'];
            
            if ($validated['return_status'] === 'scheduled') {
                $submission->scheduled_return_at = $validated['scheduled_return_at'];
                $submission->returned_at = null;
            } elseif ($validated['return_status'] === 'returned') {
                $submission->returned_at = now();
                $submission->scheduled_return_at = null;
            } else {
                $submission->returned_at = null;
                $submission->scheduled_return_at = null;
            }

            $submission->save();
        });

        return response()->json([
            'message' => 'Submission graded successfully',
            'submission' => $submission
        ]);
    }

    public function bulkGradeSubmissions(Request $request, $masterClass_id, $class_id)
    {
        $this->authorizeAccess(2, $masterClass_id, $class_id, true);

        $validated = $request->validate([
            'submissions' => 'required|array',
            'submissions.*.id' => 'required|exists:assignment_submissions,id',
            'submissions.*.score' => 'required|numeric|min:0|max:100',
            'submissions.*.feedback' => 'nullable|string',
            'return_status' => 'required|in:draft,scheduled,returned',
            'scheduled_return_at' => 'required_if:return_status,scheduled|nullable|date|after:now'
        ]);

        DB::transaction(function() use ($validated) {
            foreach ($validated['submissions'] as $submission) {
                $submissionModel = AssignmentSubmission::findOrFail($submission['id']);
                $submissionModel->score = $submission['score'];
                $submissionModel->feedback = $submission['feedback'] ?? null;
                $submissionModel->assessed_by = auth()->id();
                $submissionModel->return_status = $validated['return_status'];
                
                if ($validated['return_status'] === 'scheduled') {
                    $submissionModel->scheduled_return_at = $validated['scheduled_return_at'];
                    $submissionModel->returned_at = null;
                } elseif ($validated['return_status'] === 'returned') {
                    $submissionModel->returned_at = now();
                    $submissionModel->scheduled_return_at = null;
                } else {
                    $submissionModel->returned_at = null;
                    $submissionModel->scheduled_return_at = null;
                }

                $submissionModel->save();
            }
        });

        return response()->json([
            'message' => 'Submissions graded successfully'
        ]);
    }
}