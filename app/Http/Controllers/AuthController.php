<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Models\Owner;
use App\Models\Parentt;
use App\Models\Student;
use App\Models\Student_Parent;
use App\Models\Teacher;
use App\Models\TeacherClassroom;
use App\Models\Token;
use App\Models\User;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rules\Password as password_rule;
use App\Http\Controllers\ApiResponseTrait;
use PhpParser\Node\Expr\Cast\Object_;

class AuthController extends Controller
{
    use ApiResponseTrait;

    public function StudentRegister(Request $request): JsonResponse
    {
        $parent_id=null;
        $attributes=$request->validate([
            'first_name' => ['required', 'max:55', 'string'],
            'last_name' => ['required', 'max:55', 'string'],
            'date_of_birth'=>['required', 'date','date_format:Y-m-d'],
            'father_first_name' => ['nullable', 'max:55', 'string'],
            'father_phone_number' => ['nullable','min:10', 'max:14', 'string'],
            //'father_national_id' => ['nullable', 'string'],
            'mother_first_name'=>['nullable','max:55','string'],
            'mother_last_name'=>['nullable','max:55','string'],
            'mother_phone_number' => ['nullable','min:10', 'max:14', 'string'],
            //'mother_national_id' => ['nullable', 'string'],
            'religion' => ['required', 'max:55', 'string'],
            'national_id'=>['required', 'max:55', 'string'],
            'address'=>['required', 'string'],
            'grade_number'=>['required', 'max:12', 'integer'],
            'details' => ['nullable'],
            'have_kids'=>['required','integer'],
            'parent_id'=>['nullable','integer'],
            'gender_id'=>['required','integer'],

        ]);

        $student_role=2;
        $parent_role=3;



        if($request->have_kids==0){

            $request->validate([
                'father_first_name' => ['required', 'max:55', 'string'],
                'father_phone_number' => ['required','min:10', 'max:14', 'string'],
                //'father_national_id' => ['required', 'string'],
                'mother_first_name'=>['required','max:55','string'],
                'mother_last_name'=>['required','max:55','string'],
                'mother_phone_number' => ['required','min:10', 'max:14', 'string'],
                //'mother_national_id' => ['required', 'string'],
            ]);


            $Pinput=array();
            $Pinput['parent_password'] =  300 . rand(pow(10, 6 - 1), pow(10, 6) -1);
            $ParentpasswordDecoded=$Pinput['parent_password'];
            $Pinput['parent_password'] = bcrypt($Pinput['parent_password']);
            $Pinput['parent_email'] =   $request->father_first_name  . rand(pow(10, 4 - 1), pow(10, 4) -1). $request->mother_first_name .'@schoolname.com';
            $parent_user=User::create([
                'email' =>$Pinput['parent_email'],
                'password' => $Pinput['parent_password'],
                'role'=>$parent_role
            ]);


           try{
               $parent=Parentt::query()->create([
               'father_first_name'=>$request->father_first_name,
               'father_last_name'=>$request->last_name,
               'father_phone_number'=>$request->father_phone_number,
               'mother_first_name'=>$request->mother_first_name,
               'mother_last_name'=>$request->mother_last_name,
               'mother_phone_number'=>$request->mother_phone_number,
               'national_id'=>$request->national_id,
               'user_id'=>$parent_user->id,

//               'email'=>$Pinput['parent_email'],
//               'password'=>$Pinput['parent_password'],

           ]);

               $parent_id=$parent->parent_id;
               $parent_email=$parent_user->email;

           } catch (\Exception $e){
               if($e->getCode()==23000)
               return $this->apiResponse('The national id already exist',null,false);
           }


            $father_name=$request->father_first_name;
            $father_phone_number=$request->father_phone_number;
            $mother_first_name=$request->mother_first_name;
            $mother_last_name=$request->mother_last_name;
            $mother_phone_number=$request->mother_phone_number;

        }



        if($request->have_kids==1){



            try{
                $parent=DB::table('parents')
                ->where('national_id','=',$request->national_id)
                ->first();
                $parent_id=$parent->parent_id;

                $parent_email=DB::table('users')
                ->where('id','=',$parent->user_id)
                ->first()
                ->email;


            }catch (\Exception $e){
                if($e->getCode()==0)
                return $this->apiResponse('The national id does not exist',null,false);


            }
            $father_name=$parent->father_first_name;
            $father_phone_number=$parent->father_phone_number;
            $mother_first_name=$parent->mother_first_name;
            $mother_last_name=$parent->mother_last_name;
            $mother_phone_number=$parent->mother_phone_number;

        }



        $input=array();
        $input['student_password'] =  200 . rand(pow(10, 6 - 1), pow(10, 6) -1);
        $StudentpasswordDecoded=$input['student_password'];
        $input['student_password'] = bcrypt($input['student_password']);
        $input['student_email'] =   $request->first_name . rand(pow(10, 4 - 1), pow(10, 4) -1).'@schoolname.com';

        $student_user=User::create([
            'email' =>$input['student_email'],
            'password' => $input['student_password'],
            'role'=>$student_role
        ]);




        $student = Student::query()->create(
            [
                'first_name'=>$request->first_name,
                'last_name'=>$request->last_name,
                'date_of_birth'=>$request->date_of_birth,
                'religion'=>$request->religion,
                'address'=>$request->address,
                'grade_id'=>$request->grade_number,
                'details'=>$request->details,
//                'email'=>$input['student_email'],
//                'password'=>$input['student_password'],
                'gender_id'=>$request->gender_id,
                'parent_id'=>$parent_id,
                'user_id'=>$student_user->id,
                'status'=>'active'
            ]
        );
        if($request->have_kids==0)
            $student['ParentPasswordDecoded']=$ParentpasswordDecoded;



        $student['student_email']=$input['student_email'];
        $student['StudentPasswordDecoded']=$StudentpasswordDecoded;
        $student['father_name']=$father_name;
        $student['father_phone_number']=$father_phone_number;
        $student['mother_first_name']=$mother_first_name;
        $student['mother_last_name']=$mother_last_name;
        $student['mother_phone_number']=$mother_phone_number;
        $student['parent_email']=$parent_email;
        $student['student_token']= $student_user->createToken('token')->accessToken;


        return $this->apiResponse('success',$student);

    }


    public function TeacherRegister(Request $request): JsonResponse
    {

        $data=$request->validate([
            'first_name' => ['required', 'max:55', 'string'],
            'last_name' => ['required', 'max:55', 'string'],
            'phone_number' => ['required','min:10', 'max:14', 'string'],
            'address'=>['required', 'string'],
            'details' => 'nullable',
            'subject_id' => ['required' , ' integer'],
        ]);
        $request->validate([
            'classrooms' => ['required', 'array'],
        ]);
        $role=4;


        $input = $request->all();
        $email =   $request->first_name . rand(pow(10, 4 - 1), pow(10, 4) -1).'@schoolname.com';
        $input['password'] =  400 . rand(pow(10, 6 - 1), pow(10, 6) -1);
        $passwordDecoded=$input['password'];
        $input['password']=bcrypt($input['password']);

        $user = User::create([
            'email' => $email,
            'password' => $input['password'],
            'role' => $role,
        ]);

        $token = $user->createToken('accessToken')->accessToken;



//        $teacher = Teacher::query()->create([
//            'first_name'=>$request->first_name,
//            'last_name'=>$request->last_name,
//            'phone_number'=>$request->phone_number,
//            'subject_id'=>$request->subject_id,
//            'address'=>$request->address,
//            'details'=>$request->details,
//            'user_id'=>$user->id,
//
//
//        ]);
        $data['user_id']=$user->id;
        $teacher=Teacher::create($data);



        $teacher['email']=$email;
        $teacher['password_decoded']=$passwordDecoded;
        $teacher['token']=$token;

        foreach ($request->classrooms as $classroom_id){
            $data=[
                'teacher_id'=>$teacher->teacher_id,'classroom_id'=>$classroom_id
            ];
            TeacherClassroom::insert($data);

        }



        return $this->apiResponse('Register success',$teacher);

    }


    public function AdminRegister(Request $request): JsonResponse
    {

        $request->validate([
            'first_name' => ['required', 'max:55', 'string'],
            'last_name' => ['required', 'max:55', 'string'],

        ]);

        $role=1;


        $input = $request->all();
        $input['email'] =   $request->first_name . rand(pow(10, 4 - 1), pow(10, 4) -1).'@schoolname.com';
        $input['password'] =  100 . rand(pow(10, 6 - 1), pow(10, 6) -1);
        $passwordDecoded=$input['password'];
        $input['password']=bcrypt($input['password']);
        $input['role']=$role;





        $admin_user = User::query()->create($input);

        $admin= Admin::query()->create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'user_id'=>$admin_user->id,
        ]);

        $admin_user['first_name']=$admin->first_name;
        $admin_user['last_name']=$admin->last_name;

        $accessToken = $admin_user->createToken('token')->accessToken;
        $admin_user['password_decoded']=$passwordDecoded;
        $admin_user['token']=$accessToken;







        return $this->apiResponse('Register success',$admin_user);

    }


    //.............................................................................................



    public function login(Request $request)
    {
        $loginData = $request->validate([
            'email'=>'email|required',
            'password' => 'required',
            'FCM_token'=>'nullable'
        ]);


        $credentials = $request->only('email', 'password');


        if (Auth::attempt($credentials)) {
            $user = Auth::user();


            if(isset($request->FCM_token)){
                Token::updateOrCreate(
                    ['user_id' => $user->id],
                    ['token' => $request->FCM_token]
                );

            }

            if($user->role==0)
                return $this->OwnerLogin($request);
            else if($user->role==1)
                return $this->AdminLogin($request);
           else if($user->role==2)
               return $this->StudentLogin($request);
           else if($user->role==3)
                return $this->ParentLogin($request);
           else if($user->role==4)
                return $this->TeacherLogin($request);





        } else {
            return $this->apiResponse('The email or password are incorrect',null,false);
        }
    }

    public function StudentLogin(Request $request): JsonResponse
    {
        $student = new \stdClass();
        $student->email =Auth::user()->email;
        $student->role=2;
        $student->token = Auth::user()->createToken('accessToken')->accessToken;

        return $this->apiResponse('login successfully',$student);


    }


    public function AdminLogin(Request $request): JsonResponse
    {

        $admin=DB::table('admins')
            ->where('user_id','=',Auth::id())
            ->first();

        $admin->token=Auth::user()->createToken('accessToken')->accessToken;

        return $this->apiResponse('login successfully',$admin);


    }


    public function ParentLogin(Request $request): JsonResponse
    {

        $parent = new \stdClass();
        $parent->email =Auth::user()->email;
        $parent->role=3;
        $parent->token = Auth::user()->createToken('accessToken')->accessToken;

        return $this->apiResponse('login successfully',$parent);


    }


    public function OwnerLogin(Request $request): JsonResponse
    {

        $owner=DB::table('owners')
            ->where('user_id','=',Auth::id())
            ->first();


        $token=Auth::user()->createToken('accessToken')->accessToken;

        $res = [
            'first_name' => 'Omar',
            'last_name' => 'Omarain',
            'user_id'=>$owner->user_id,
            'created_at'=>$owner->created_at,
            'updated_at'=>$owner->updated_at,
            'token'=>$token
        ];

        return $this->apiResponse('login successfully',$res);



    }


    public function TeacherLogin(Request $request): JsonResponse
    {

        $teacher = new \stdClass();
        $teacher->email = Auth::user()->email;
        $teacher->role=4;
        $teacher->token=Auth::user()->createToken('accessToken')->accessToken;

        return $this->apiResponse('login successfully',$teacher);



    }


    //.............................................................................................


    public function logout(): JsonResponse
    {
        DB::table('tokens')
            ->where('user_id',Auth::id())
            ->update([
                'token' => '0'
            ]);
        Auth::User()->token()->revoke();


        return $this->apiResponse('logged out successfully');
    }

    public function resetPassword(Request $request){
        $request->validate([
            'email'=>['required','string'],
            'password'=>['required','string']
        ]);
        $user =DB::table('users')
            ->where('email',$request->email)
            ->first();
        if(!$user)
            return $this->apiResponse('user not found',null,false);
        $user=DB::table('users')
            ->update(['password'=>bcrypt($request->password)]);

        return $this->apiResponse('success');
    }





}
