<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User_log\UserLogList;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class UserLogListSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $lists = [
            // Authentication
            [
                'category_id' => '1',
                'route_name' => 'login.submit',
                'method' => 'POST',
                'description' => 'User Login Process',
            ],
            [
                'category_id' => '1',
                'route_name' => 'login',
                'method' => 'GET',
                'description' => 'User Login',
            ],
            [
                'category_id' => '1',
                'route_name' => 'logout',
                'method' => 'POST',
                'description' => 'User Logout',
            ],
            [
                'category_id' => '1',
                'route_name' => 'logout',
                'method' => 'GET',
                'description' => 'User Logout',
            ],
            [
                'category_id' => '1',
                'route_name' => 'home',
                'method' => 'GET',
                'description' => 'Home Page',
            ],
            [
                'category_id' => '1',
                'route_name' => 'dashboard',
                'method' => 'GET',
                'description' => 'User View Dashboard',
            ],
            [
                'category_id' => '1',
                'route_name' => 'csrf.refresh',
                'method' => 'POST',
                'description' => 'Refresh CSRF Token',
            ],

            // Email Verification & Password Reset
            [
                'category_id' => '2',
                'route_name' => 'verification.notice',
                'method' => 'GET',
                'description' => 'Email Verification Status',
            ],
            [
                'category_id' => '2',
                'route_name' => 'verification.resend',
                'method' => 'POST',
                'description' => 'Resend Email Verification',
            ],
            [
                'category_id' => '2',
                'route_name' => 'email.verify-new',
                'method' => 'GET',
                'description' => 'Email Verification Check New Email',
            ],
            [
                'category_id' => '2',
                'route_name' => 'verification.verify',
                'method' => 'GET',
                'description' => 'Email Verification Check',
            ],
            [
                'category_id' => '2',
                'route_name' => 'password.index',
                'method' => 'GET',
                'description' => 'Password Reset Form',
            ],
            [
                'category_id' => '2',
                'route_name' => 'password.request',
                'method' => 'GET',
                'description' => 'Password Reset Form',
            ],
            [
                'category_id' => '2',
                'route_name' => 'password.email',
                'method' => 'PATCH',
                'description' => 'Send Password Reset Link to Email',
            ],
            [
                'category_id' => '2',
                'route_name' => 'password.update',
                'method' => 'PATCH',
                'description' => 'Password Reset Process',
            ],
            [
                'category_id' => '2',
                'route_name' => 'password.reset',
                'method' => 'GET',
                'description' => 'Password Reset',
            ],
            [
                'category_id' => '2',
                'route_name' => 'password.update',
                'method' => 'POST',
                'description' => 'Password Reset Process',
            ],

            // Role CRUD
            [
                'category_id' => '3',
                'route_name' => 'admin.authentication.roles.view',
                'method' => 'GET',
                'description' => 'Role List',
            ],
            [
                'category_id' => '3',
                'route_name' => 'admin.authentication.roles.store',
                'method' => 'POST',
                'description' => 'Role Store',
            ],
            [
                'category_id' => '3',
                'route_name' => 'admin.authentication.roles.update',
                'method' => 'PUT',
                'description' => 'Role Update',
            ],
            [
                'category_id' => '3',
                'route_name' => 'admin.authentication.roles.destroy',
                'method' => 'DELETE',
                'description' => 'Role Delete',
            ],

            // User Account CRUD
            [
                'category_id' => '4',
                'route_name' => 'admin.authentication.users.view',
                'method' => 'GET',
                'description' => 'User List',
            ],
            [
                'category_id' => '4',
                'route_name' => 'admin.authentication.users.create',
                'method' => 'GET',
                'description' => 'User Create Form',
            ],
            [
                'category_id' => '4',
                'route_name' => 'admin.authentication.users.store',
                'method' => 'POST',
                'description' => 'User Store',
            ],
            [
                'category_id' => '4',
                'route_name' => 'admin.authentication.users.update',
                'method' => 'PUT',    
                'description' => 'User Update',
            ],
            [
                'category_id' => '4',
                'route_name' => 'admin.authentication.users.destroy',
                'method' => 'DELETE',
                'description' => 'User Delete',
            ],
            
            // Profile Update By User
            [
                'category_id' => '5',
                'route_name' => 'profile.index',
                'method' => 'GET',
                'description' => 'User View Profile',
            ],
            [
                'category_id' => '5',
                'route_name' => 'profile.view',
                'method' => 'GET',
                'description' => 'User View Profile',
            ],
            [
                'category_id' => '5',
                'route_name' => 'profile.edit',
                'method' => 'GET',    
                'description' => 'User Edit Profile',
            ],
            [
                'category_id' => '5',
                'route_name' => 'profile.update',
                'method' => 'PATCH',
                'description' => 'User Update Profile',
            ],
            [
                'category_id' => '5',
                'route_name' => 'profile.changepassword.edit',
                'method' => 'GET',
                'description' => 'User Change Password Form',
            ],
            [
                'category_id' => '5',
                'route_name' => 'profile.changepassword.update',
                'method' => 'PATCH',
                'description' => 'User Change Password',
            ],

            // Academic Year CRUD
            [
                'category_id' => '6',
                'route_name' => 'school.academicYear.view',
                'method' => 'GET',
                'description' => 'Academic Year List',
            ],
            [
                'category_id' => '6',
                'route_name' => 'school.academicYear.store',
                'method' => 'POST',
                'description' => 'Academic Year Store',
            ],
            [
                'category_id' => '6',
                'route_name' => 'school.academicYear.update',
                'method' => 'PUT',
                'description' => 'Academic Year Update',
            ],
            [
                'category_id' => '6',
                'route_name' => 'school.academicYear.destroy',
                'method' => 'DELETE',
                'description' => 'Academic Year Delete',
            ],

            // Subject CRUD
            [
                'category_id' => '7',
                'route_name' => 'classroom.subject.view',
                'method' => 'GET',
                'description' => 'Subject List',
            ],
            [
                'category_id' => '7',
                'route_name' => 'classroom.subject.store',
                'method' => 'POST',
                'description' => 'Subject Store',
            ],
            [
                'category_id' => '7',
                'route_name' => 'classroom.subject.update',
                'method' => 'PUT',
                'description' => 'Subject Update',
            ],
            [
                'category_id' => '7',
                'route_name' => 'classroom.subject.destroy',
                'method' => 'DELETE',
                'description' => 'Subject Delete',
            ],

            // Master Class CRUD
            [
                'category_id' => '8',
                'route_name' => 'classroom.masterClass.view',
                'method' => 'GET',
                'description' => 'Master Class List',
            ],
            [
                'category_id' => '8',
                'route_name' => 'classroom.masterClass.store',
                'method' => 'POST',
                'description' => 'Master Class Store',
            ],
            [
                'category_id' => '8',
                'route_name' => 'classroom.masterClass.update',
                'method' => 'PUT',
                'description' => 'Master Class Update',
            ],
            [
                'category_id' => '8',
                'route_name' => 'classroom.masterClass.destroy',
                'method' => 'DELETE',
                'description' => 'Master Class Delete',
            ],

            // Master Class Student CRUD
            [
                'category_id' => '9',
                'route_name' => 'master_class_students.view_students',
                'method' => 'GET',
                'description' => 'Master Class Student List',
            ],
            [
                'category_id' => '9',
                'route_name' => 'master_class_students.create_students',
                'method' => 'GET',
                'description' => 'Master Class Student Create',
            ],
            [
                'category_id' => '9',
                'route_name' => 'master_class_students.store_student',
                'method' => 'POST',
                'description' => 'Master Class Student Store',
            ],
            [
                'category_id' => '9',
                'route_name' => 'master_class_students.delete',
                'method' => 'DELETE',
                'description' => 'Master Class Student Delete',
            ],

            // Class List CRUD
            [
                'category_id' => '10',
                'route_name' => 'classroom.masterClass.manage.index',
                'method' => 'GET',
                'description' => 'Class List List',
            ],
            [
                'category_id' => '10',
                'route_name' => 'class_list.add',
                'method' => 'GET',
                'description' => 'Class List Add Form',
            ],
            [
                'category_id' => '10',
                'route_name' => 'class_list.store',
                'method' => 'POST',
                'description' => 'Class List Store',
            ],
            [
                'category_id' => '10',
                'route_name' => 'class_list.update',
                'method' => 'PUT',
                'description' => 'Class List Update',
            ],
            [
                'category_id' => '10',
                'route_name' => 'class_list.destroy',
                'method' => 'DELETE',
                'description' => 'Class List Delete',
            ],
            [
                'category_id' => '10',
                'route_name' => 'classroom.index',
                'method' => 'GET',
                'description' => 'Class Info',
            ],

            // Class List Teacher CRUD
            [
                'category_id' => '11',
                'route_name' => 'class_lists.teacher.index',
                'method' => 'POST',
                'description' => 'Class List Teacher View',
            ],
            [
                'category_id' => '11',
                'route_name' => 'class_lists.teacher.store',
                'method' => 'POST',
                'description' => 'Class List Teacher Store',
            ],
            [
                'category_id' => '11',
                'route_name' => 'class_list.teacher.destroy',
                'method' => 'DELETE',
                'description' => 'Class List Teacher Delete',
            ],
            [
                'category_id' => '11',
                'route_name' => 'classroom.teacher.index',
                'method' => 'GET',
                'description' => 'Classroom Teacher View',
            ],

            // Class List Student CRUD
            [
                'category_id' => '12',
                'route_name' => 'class_lists.student.index',
                'method' => 'POST',
                'description' => 'Class List Student View',
            ],
            [
                'category_id' => '12',
                'route_name' => 'class_lists.student.store',
                'method' => 'POST',
                'description' => 'Class List Student Store',
            ],
            [
                'category_id' => '12',
                'route_name' => 'class_lists.student.destroy',
                'method' => 'DELETE',
                'description' => 'Class List Student Delete',
            ],

            // Class List (Classroom), this action only for teacher role
            [
                'category_id' => '12',
                'route_name' => 'classroom.student.index',
                'method' => 'POST',
                'description' => 'Classroom Student View',
            ],
            [
                'category_id' => '12',
                'route_name' => 'classroom.student.store',
                'method' => 'POST',
                'description' => 'Classroom Student Store',
            ],
            [
                'category_id' => '12',
                'route_name' => 'classroom.student.destroy',
                'method' => 'DELETE',
                'description' => 'Classroom Student Delete',
            ],

            // Classroom Topic CRUD
            [
                'category_id' => '13',
                'route_name' => 'classroom.topic.index',
                'method' => 'GET',
                'description' => 'Classroom Topic List',
            ],
            [
                'category_id' => '13',
                'route_name' => 'classroom.topic.store',
                'method' => 'POST',
                'description' => 'Classroom Topic Store',
            ],
            [
                'category_id' => '13',
                'route_name' => 'classroom.topic.update',
                'method' => 'PUT',
                'description' => 'Classroom Topic Update',
            ],
            [
                'category_id' => '13',
                'route_name' => 'classroom.topic.destroy',
                'method' => 'DELETE',
                'description' => 'Classroom Topic Delete',
            ],

            // Classroom Attendance CRUD
            [
                'category_id' => '14',
                'route_name' => 'classroom.attendance.index',
                'method' => 'GET',
                'description' => 'Classroom Attendance List',
            ],
            [
                'category_id' => '14',
                'route_name' => 'classroom.attendance.store',
                'method' => 'POST',
                'description' => 'Classroom Attendance Store',
            ],
            [
                'category_id' => '14',
                'route_name' => 'classroom.attendance.update',
                'method' => 'PUT',
                'description' => 'Classroom Attendance Update',
            ],
            [
                'category_id' => '14',
                'route_name' => 'classroom.attendance.destroy',
                'method' => 'DELETE',
                'description' => 'Classroom Attendance Delete',
            ],

            // Classroom Presences (Only Read & Update)
            [
                'category_id' => '15',
                'route_name' => 'classroom.presence.index',
                'method' => 'GET',
                'description' => 'Classroom Presence List',
            ],
            [
                'category_id' => '15',
                'route_name' => 'classroom.presence.bulkUpdate',
                'method' => 'PUT',
                'description' => 'Classroom Presence Update',
            ],

            // Teacher Resource CRUD
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.index',
                'method' => 'GET',
                'description' => 'Teacher Resource List',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.store',
                'method' => 'POST',
                'description' => 'Teacher Resource Store',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.show',
                'method' => 'GET',
                'description' => 'Teacher Resource List',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.update',
                'method' => 'PUT',
                'description' => 'Teacher Resource Update',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.destroy',
                'method' => 'DELETE',
                'description' => 'Teacher Resource Delete',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.submissions',
                'method' => 'GET',
                'description' => 'Teacher Resource Submission List',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.preview-submission',
                'method' => 'GET',
                'description' => 'Teacher Resource Submission Preview',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.grade',
                'method' => 'POST',
                'description' => 'Teacher Resource Submission Grade',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.store-feedback',
                'method' => 'POST',
                'description' => 'Teacher Resource Submission Store Feedback (Private Comment)',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.delete-feedback',
                'method' => 'DELETE',
                'description' => 'Teacher Resource Submission Delete Feedback (Private Comment)',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.bulk-grade',
                'method' => 'POST',
                'description' => 'Teacher Resource Submission Bulk Grade',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.update-score',
                'method' => 'POST',
                'description' => 'Teacher Resource Submission Update Score',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.bulk-return-submissions',
                'method' => 'POST',
                'description' => 'Teacher Resource Submission Bulk Return',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.view-attachment',
                'method' => 'GET',
                'description' => 'Teacher Resource Submission Download',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.resources.download-attachment',
                'method' => 'GET',
                'description' => 'Teacher Resource Submission Delete',
            ],
            [
                'category_id' => '16',
                'route_name' => 'classroom.person.all',
                'method' => 'GET',
                'description' => 'Teacher Resource Submission Bulk Return',
            ],

            // Student Master Class
            [
                'category_id' => '17',
                'route_name' => 'master-class.enrolled-class',
                'method' => 'GET',
                'description' => 'Student Master Class List',
            ],
            [
                'category_id' => '17',
                'route_name' => 'master-class.archived-class',
                'method' => 'GET',
                'description' => 'Student Master Class List',
            ],
            [
                'category_id' => '17',
                'route_name' => 'master-class.exited-class',
                'method' => 'GET',
                'description' => 'Student Master Class List',
            ],
            [
                'category_id' => '17',
                'route_name' => 'master-class.join-class',
                'method' => 'POST',
                'description' => 'Student Master Class Join',
            ],
            [
                'category_id' => '17',
                'route_name' => 'master-class.exit-class',
                'method' => 'PUT',
                'description' => 'Student Master Class Exit',
            ],
            [
                'category_id' => '17',
                'route_name' => 'master-class.rejoin-class',
                'method' => 'PUT',
                'description' => 'Student Master Class Rejoin',
            ],

            // Student Classroom
            [
                'category_id' => '18',
                'route_name' => 'master-class.classroom',
                'method' => 'GET',
                'description' => 'Student Classroom List',
            ],
            [
                'category_id' => '18',
                'route_name' => 'master-class.classroom.join-class',
                'method' => 'POST',
                'description' => 'Student Classroom Join',
            ],

            // Student Resource, Submission, View & Download Content
            [
                'category_id' => '19',
                'route_name' => 'student.classroom.resources.index',
                'method' => 'GET',
                'description' => 'Student Resource List',
            ],
            [
                'category_id' => '19',
                'route_name' => 'student.classroom.resources.show',
                'method' => 'GET',
                'description' => 'Student Resource Show',
            ],
            [
                'category_id' => '19',
                'route_name' => 'student.classroom.resources.submissions.store',
                'method' => 'POST',
                'description' => 'Student Resource Submission Store',
            ],
            [
                'category_id' => '19',
                'route_name' => 'student.classroom.resources.submissions.complete',
                'method' => 'POST',
                'description' => 'Student Mark As Done Submission',
            ],
            [
                'category_id' => '19',
                'route_name' => 'student.classroom.resources.submissions.cancel',
                'method' => 'POST',
                'description' => 'Student Cancel Submission',
            ],
            [
                'category_id' => '19',
                'route_name' => 'student.classroom.resources.store-feedback',
                'method' => 'POST',
                'description' => 'Student Resource Submission Store Feedback',
            ],
            [
                'category_id' => '19',
                'route_name' => 'student.classroom.resources.delete-feedback',
                'method' => 'POST',
                'description' => 'Student Resource Submission Delete Feedback',
            ],
            [
                'category_id' => '19',
                'route_name' => 'student.classroom.resources.view-attachment',
                'method' => 'GET',
                'description' => 'Student Resource Submission Download',
            ],
            [
                'category_id' => '19',
                'route_name' => 'student.classroom.resources.download-attachment',
                'method' => 'GET',
                'description' => 'Student Resource Submission Delete',
            ],
            [
                'category_id' => '19',
                'route_name' => 'student.classroom.person.all',
                'method' => 'GET',
                'description' => 'Student Classroom Person List',
            ],
            [
                'category_id' => '19',
                'route_name' => 'student.classroom.presence.index',
                'method' => 'GET',
                'description' => 'Student Classroom Presence List',
            ],
        ];

        foreach ($lists as $list) {
            UserLogList::create($list);
        }
    }
}
