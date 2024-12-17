<?php

namespace App\Http\Controllers\Classroom;

use Illuminate\Http\Request;
use App\Models\Classroom\Topic;
use App\Models\Classroom\ClassList;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Classroom\Traits\AuthorizesClassAccess;

class TopicController extends Controller
{
    use AuthorizesClassAccess;

    public function index($masterClass_id, $class_id)
    {
        $this->authorizeAccess(2, $masterClass_id, $class_id, false);

        $classList = ClassList::where('id', $class_id)->firstOrFail();
        // $classList = new \stdClass();
        // $classList->id = $class_id;
        
        $topics = Topic::where('class_id', $class_id)->get();
        
        return view('dashboard.classroom.class_list.topic.main_view', [
            'page_title' => 'Class: ' . $classList->class_name . ' - Topic Management',
            'topics' => $topics,
            'masterClass_id' => $masterClass_id,
            'classList' => $classList
        ]);
    }
    public function store(Request $request, $masterClass_id, $class_id)
    {
        $authorization = $this->authorizeAccess(2, $masterClass_id, $class_id, true);
        if ($authorization !== true) {
            return $authorization;
        }

        $validator = Validator::make($request->all(), [
            'topic_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Nama topik wajib diisi.'], 422);
        }

        $topic = Topic::create([
            'class_id' => $class_id,
            'topic_name' => $request->topic_name,
            'updated_at' => null,
        ]);

        if(!$topic) {
            return response()->json(['error' => 'Topik gagal dibuat.'], 500);
        }

        return response()->json([
            'success' => true,
            'message' => 'Topik berhasil dibuat.',
        ]);
    }

    public function update(Request $request, $masterClass_id, $class_id, $topic_id)
    {
        $authorization = $this->authorizeAccess(2, $masterClass_id, $class_id, true);
        if ($authorization !== true) {
            return $authorization;
        }

        $validator = Validator::make($request->all(), [
            'topic_name' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => 'Nama topik wajib diisi.'], 422);
        }

        $topic = Topic::where('id', $topic_id)->where('class_id', $class_id)->firstOrFail();
        $topic->update([
            'topic_name' => $request->topic_name,
        ]);

        return response()->json(['success' => 'Topik berhasil diperbarui.']);
    }

    public function destroy($masterClass_id, $class_id, $topic_id)
    {
        $authorization = $this->authorizeAccess(2, $masterClass_id, $class_id, true);
        if ($authorization !== true) {
            return $authorization;
        }

        $topic = Topic::where('id', $topic_id)->where('class_id', $class_id)->firstOrFail();
        $topic->delete();

        return response()->json(['success' => 'Topik berhasil dihapus.']);
    }
}