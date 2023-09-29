<?php

use App\Events\StudentAttendanceEvent;
use App\Http\Controllers\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;


//
Route::post('/python-script', [\App\Http\Controllers\PythonController::class , 'answer']);

//............................register routes.............................................
//Owner routes
Route::middleware(['auth:api', 'isOwner'])->group(function () {
    Route::post('/admin/register',[AuthController::class, 'AdminRegister']);
    Route::get('/admin/index',[\App\Http\Controllers\AdminController::class, 'index']);
    Route::get('/admin/show/{id}',[\App\Http\Controllers\AdminController::class, 'show']);

});

//............................Login and Logout route...................................
Route::post('login', [AuthController::class, 'login']);


Route::middleware('auth:api')->group(function () {
    Route::get('logout', [AuthController::class, 'logout']);


    //Likes and Comments Routes
    Route::get('/like/{post_id}',[\App\Http\Controllers\LikeController::class, 'store']);
    Route::get('/show_all_likes/{post_id}',[\App\Http\Controllers\LikeController::class, 'index']);
    Route::get('/show_all_comments/{post_id}',[\App\Http\Controllers\CommentController::class, 'index']);
    Route::get('/delete_comment/{comment_id}',[\App\Http\Controllers\CommentController::class, 'destroy']);
    Route::post('/create_comment',[\App\Http\Controllers\CommentController::class, 'store']);
    Route::post('/update_comment',[\App\Http\Controllers\CommentController::class, 'update']);

    //Posts
    Route::get('/get_posts', [\App\Http\Controllers\PostController::class, 'getPosts']);

    //exam schedule for specific grade
    Route::get('/exams_schedule/showByGrade/{grade_id}', [\App\Http\Controllers\ExamScheduleController::class, 'showByGrade']);


    Route::get('/logout', [\App\Http\Controllers\AuthController::class, 'logout']);

});

//.....................................................................................

//Admin routes
Route::middleware(['auth:api', 'isAdmin'])->group(function () {


});




//Admin or Owner routes
Route::middleware(['auth:api', 'isAdminOrOwner'])->group(function () {
    //register routes
    Route::post('/student/register',[AuthController::class, 'StudentRegister']);
    Route::post('/teacher/register',[AuthController::class, 'TeacherRegister']);


    //About Us Routes
    Route::get('/admin/show_school/{id}', [\App\Http\Controllers\SchoolController::class, 'show']);
    Route::post('/admin/update_school/{id}', [\App\Http\Controllers\SchoolController::class, 'update']);


    //Teacher  Routes
    Route::get('/teachers', [\App\Http\Controllers\TeacherController::class, 'index']);
    Route::get('/teacher/{id}', [\App\Http\Controllers\TeacherController::class, 'show']);
    Route::get('/delete_teacher/{id}', [\App\Http\Controllers\TeacherController::class, 'destroy']);
    Route::get('/subject_teachers/{id}', [\App\Http\Controllers\TeacherController::class, 'subjectTeacher']);
    Route::get('/search_teacher', [\App\Http\Controllers\TeacherController::class, 'searchByName']);
    Route::post('/teacher/register', [\App\Http\Controllers\AuthController::class, 'TeacherRegister']);
    Route::post('/update_teacher/{id}', [\App\Http\Controllers\TeacherController::class, 'update']);


    //teachers classrooms routes
    Route::get('/teachers_of_classrooms', [\App\Http\Controllers\TeacherClassroomController::class, 'index']);



    //Attendance routes
        /*Route::Post('/storeattendance', [\App\Http\Controllers\AttendanceController::class, 'store'],function(){
            event(new StudentAttendanceEvent(['message' => 'Hello world']));;
        });*/

    Route::post('/storeattendance', [\App\Http\Controllers\AttendanceController::class, 'store']);
    Route::post('sendNotification', [\App\Http\Controllers\AttendanceController::class, 'sendNotification']);
    Route::get('/Ashowattendance/{id}', [\App\Http\Controllers\AttendanceController::class, 'showforadmin']);


    //Notices Routes
    Route::Post('/storenotice', [\App\Http\Controllers\NoticeController::class, 'store']);
    Route::get('/Ashownotice/{id}', [\App\Http\Controllers\NoticeController::class, 'showforadmin']);



    //get numbers of students,teachers,Admins to show it in the dashboard
    Route::get('/admin/stats',[\App\Http\Controllers\AdminController::class, 'getStatistics']);


    //exams schedule
    Route::Post('/exams_schedule/create', [\App\Http\Controllers\ExamScheduleController::class, 'create']);
    Route::get('/exams_schedule/index', [\App\Http\Controllers\ExamScheduleController::class, 'index']);


    //complaint

    Route::get('/admin/complaints', [\App\Http\Controllers\AdminController::class, 'ComplaintIndex']);
    Route::post('/admin/complaints/{complaint}', [\App\Http\Controllers\AdminController::class, 'ComplaintResolve']);


    //Exams Routes
    Route::Post('/exams/store', [\App\Http\Controllers\ExamController::class, 'store']);
    Route::get('/exams/show/{id}', [\App\Http\Controllers\ExamController::class, 'showForStudent']);
    Route::Post('/exams/show_for_admin', [\App\Http\Controllers\ExamController::class, 'showForAdmin']);



    //Schedule Routes
    Route::post('/create_schedule', [\App\Http\Controllers\ScheduleController::class, 'create']);
    Route::post('/show_schedule_classroom', [\App\Http\Controllers\ScheduleController::class, 'showClassroomSchedule']);
    Route::get('/show_teacher_schedule/{id}/{day_number}', [\App\Http\Controllers\ScheduleController::class, 'showTeachersScheduleForAdmin']);
    Route::get('/index_schedule', [\App\Http\Controllers\ScheduleController::class, 'index']);




    //Subject Routes
    Route::get('/subjects', [\App\Http\Controllers\SubjectController::class, 'index']);
    Route::get('/subject/{id}', [\App\Http\Controllers\SubjectController::class, 'show']);
    Route::post('/create_subject', [\App\Http\Controllers\SubjectController::class, 'store']);
    Route::post('/update_subject/{id}', [\App\Http\Controllers\SubjectController::class, 'update']);
    Route::get('/delete_subject/{id}', [\App\Http\Controllers\SubjectController::class, 'destroy']);
    Route::get('/search_subject/{name}', [\App\Http\Controllers\SubjectController::class, 'searchByName']);


    //School Years Routes
    Route::get('/schoolyears', [\App\Http\Controllers\SchoolYearController::class, 'index']);
    Route::post('/create_schoolyear', [\App\Http\Controllers\SchoolYearController::class, 'store']);
    //Route::get('/delete_schoolyear/{id}', [\App\Http\Controllers\SchoolYearController::class, 'destroy']);



    //Student Routes
    Route::post('/update_student/{student_id}', [\App\Http\Controllers\StudentController::class, 'update']);
    Route::get('/students/index', [\App\Http\Controllers\StudentController::class, 'index']);
    Route::delete('delete_student/{student_id}', [\App\Http\Controllers\StudentController::class, 'destroy']);
    Route::get('/show_by_grade/{grade_id}', [\App\Http\Controllers\StudentController::class, 'showByGrade']);
    Route::get('/show_student/{student_id}', [\App\Http\Controllers\StudentController::class, 'show']);
    Route::post('/show_by_classroomAndGrade', [\App\Http\Controllers\StudentController::class, 'showByClassroom']);
    Route::get('/search_student', [\App\Http\Controllers\StudentController::class, 'searchByName']);



    //Classroom Routes
    Route::post('/create_classroom', [\App\Http\Controllers\ClassroomController::class, 'store']);
    Route::get('/classrooms/index', [\App\Http\Controllers\ClassroomController::class, 'index']);
    Route::get('/classrooms/showByGrade/{grade_id}', [\App\Http\Controllers\ClassroomController::class, 'showByGrade']);
    Route::post('/createOneStudent', [\App\Http\Controllers\StudentClassroomController::class, 'create']);
    Route::get('/classrooms/clear', [\App\Http\Controllers\StudentClassroomController::class, 'clearClassrooms']);
    Route::post('/classroom/update/student', [\App\Http\Controllers\StudentClassroomController::class, 'update']);



    //files Routes
    Route::post('/show_files_classroom',[\App\Http\Controllers\FileController::class, 'showForClassroom']);

    //Posts Routes
    Route::post('/create_for_student', [\App\Http\Controllers\PostController::class, 'createForStudent']);
    Route::post('/create_for_grade', [\App\Http\Controllers\PostController::class, 'createForGrade']);
    Route::post('/create_for_school', [\App\Http\Controllers\PostController::class, 'createForSchool']);

    //Results Routes
    Route::post('/calcResult', [\App\Http\Controllers\ResultController::class, 'calcResForGrade']);
    Route::post('/upgrade_students', [\App\Http\Controllers\ResultController::class, 'upgradeStudents']);
    Route::get('/show_result/{student_id}', [\App\Http\Controllers\ResultController::class, 'showResultStudent']);
    Route::post('/show_students_results', [\App\Http\Controllers\ResultController::class, 'showStudents']);

    //Student history
    Route::get('/show_history/{student_id}', [\App\Http\Controllers\StudentHistoryController::class, 'showStudentHistory']);


    //employee
    Route::get('/employee/index', [\App\Http\Controllers\EmployeeController::class, 'index']);
    Route::get('/employee/{id}', [\App\Http\Controllers\EmployeeController::class, 'show']);
    Route::get('/delete_employee/{id}', [\App\Http\Controllers\EmployeeController::class, 'destroy']);
    Route::post('/create_employee', [\App\Http\Controllers\EmployeeController::class, 'store']);
    Route::post('/update_employee/{id}', [\App\Http\Controllers\EmployeeController::class, 'update']);
    Route::post('/delete_employee/{id}', [\App\Http\Controllers\EmployeeController::class, 'update']);


    //books
    Route::post('/addbook',[\App\Http\Controllers\BookController::class, 'store']);
    Route::get('/admin/showbooks',[\App\Http\Controllers\BookController::class, 'showAdmin']);
    Route::delete('/deletebook/{id}',[\App\Http\Controllers\BookController::class, 'deleteBook']);
    Route::post('/updatebook',[\App\Http\Controllers\BookController::class, 'updateBook']);

    //password
    Route::post('/resetPassword',[\App\Http\Controllers\AuthController::class, 'resetPassword']);






});



//Parent routes
Route::middleware(['auth:api', 'isParent'])->group(function () {
    //show attendence
    Route::get('/Pshowattendance/{id}', [\App\Http\Controllers\AttendanceController::class, 'showforparent']);

    //show notices
    Route::get('/Pshownotice/{id}', [\App\Http\Controllers\NoticeController::class, 'showforparent']);

    //complaints
    Route::post('/complaints', [\App\Http\Controllers\ComplaintController::class, 'store']);
    Route::get('/parent/complaints', [\App\Http\Controllers\ComplaintController::class, 'getParentComplaints']);

    //exams
    Route::post('/parent/showexams/{student_id}', [\App\Http\Controllers\ExamController::class, 'showForParent']);

    //files
    Route::get('/parent/showfiles/{student_id}', [\App\Http\Controllers\FileController::class, 'showForParent']);

    //books
    Route::get('/parent/showbooks/{student_id}', [\App\Http\Controllers\BookController::class, 'showParent']);

    //schedule
    Route::post('/parent/showschedule/{student_id}', [\App\Http\Controllers\ScheduleController::class, 'showClassroomScheduleParent']);

    //post
    Route::get('/parent/showposts/{student_id}', [\App\Http\Controllers\PostController::class, 'showParent']);

    //home
    Route::get('/parent/showhome', [\App\Http\Controllers\ParenttController::class, 'showHome']);

    //profile
    Route::get('/parent/profile', [\App\Http\Controllers\ParenttController::class, 'profile']);

});

//Student routes
Route::middleware(['auth:api', 'isStudent'])->group(function () {

    //show attendence
    Route::get('/Sshowattendance', [\App\Http\Controllers\AttendanceController::class, 'showforstudent']);

    //show notices
    Route::get('/Sshownotices', [\App\Http\Controllers\NoticeController::class, 'showforstudent']);


    Route::get('/student/profile', [\App\Http\Controllers\StudentController::class, 'showProfile']);
    Route::get('/student/home', [\App\Http\Controllers\StudentController::class, 'showHome']);
    Route::post('/show_schedule', [\App\Http\Controllers\ScheduleController::class, 'showClassroomScheduleStudent']);

    //Exams
    Route::Post('/exams/show_for_student', [\App\Http\Controllers\ExamController::class, 'showForStudent']);

    //Files
    Route::get('/show_file_student', [\App\Http\Controllers\FileController::class, 'showForStudent']);

    //books
    Route::get('/student/showbooks',[\App\Http\Controllers\BookController::class, 'showStudent']);

    Route::get('/student/showExamSchedule',[\App\Http\Controllers\ExamScheduleController::class, 'showToken']);




});

//Students or Parents routes
Route::middleware(['auth:api', 'isStudentOrParent'])->group(function () {

});

//Teacher routes
Route::middleware(['auth:api', 'isTeacher'])->group(function () {

    //profile
    Route::get('/profile/teacher', [\App\Http\Controllers\TeacherController::class,'showProfile']);
    //get Schedules
    ///
    Route::get('/teacher/getschedule/{day_number}',[\App\Http\Controllers\ScheduleController::class, 'showTeachersSchedule']);

    Route::get('/home/teacher', [\App\Http\Controllers\TeacherController::class,'showHome']);



});

//Student or Parent or Teacher routes
Route::middleware(['auth:api', 'isStudentOrParentOrTeacher'])->group(function () {

});


//Owner or Admin or Teacher routes
Route::middleware(['auth:api', 'isAdminOrOwnerOrTeacher'])->group(function () {
    Route::post('/create_for_classroom', [\App\Http\Controllers\PostController::class, 'createForClassroom']);
    Route::get('/delete_post/{post_id}',[\App\Http\Controllers\PostController::class, 'deletePost']);
    Route::post('/edit_post/{post_id}',[\App\Http\Controllers\PostController::class, 'updatePost']);
    Route::post('/upload_file',[\App\Http\Controllers\FileController::class, 'upload']);
    Route::get('/posts', [\App\Http\Controllers\PostController::class, 'getAllPosts']);



});


