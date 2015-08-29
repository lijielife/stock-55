<?php namespace App\Http\Controllers\Admins;
use Auth;
use Illuminate\Routing\Controller;
use App\Http\Requests\Admins\LoginRequest;
use App\Models\Users;
use Session;

class LoginController extends Controller {
	
	public function getIndex(){
		if(Auth::check()){
			return redirect('/admin/index');
		}else{
			return view('admin.login');
		}
	}
	public function postProcess(LoginRequest $request){
		$username = $request->input('username');
		$password = $request->input('password');
		//echo '[Username : '. $username .'][Password : '. $password .']';
		
		if(Auth::attempt(['username' => $username,'password'=>$password,'type'=>'admin'],$request->has('remember'))){
                    $users = Users::where('active', 'Y')->where('username', $username)->first();
                    Session::put('username', $users);
                    return redirect()->intended('/admin/index');
		}else{
                    return redirect()->back()->with('message',"Error!! Username or Password Incorrect. \nPlease try again.");;
		}
	}
	
	public function getLogout(){
		Auth::logout();
		return redirect('/admin/login');
	}
}