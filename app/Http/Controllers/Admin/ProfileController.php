<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

use App\Profile;

use App\History;

use Carbon\Carbon;

class ProfileController extends Controller


{
    public function add()
    {
     
        return view('admin.profile.create');
    }

    public function create(Request $request)
    {
      $hoge = "hello";
      //千行くらいあると仮定
      //$this->validate($request, Profile::$rules);
      
      $profile = new Profile;
      //フォームに入力された値を一括で取得
      $form = $request->all(); 
      dd($form);
      //モデルのプロパティに一括で代入
      $profile->fill($form);
      // $profile->name = $request->name;
      // $profile->gender = $request->gender;
      // $profile->hobby = $request->hobby;
      // $profile->introduction = $request->introduction;
      $profile->save();
      return redirect('admin/profile/create');
    }

    public function edit(Request $request)
    {
      $profile = Profile::find($request->id);
      if (empty($profile)) {
        abort(404);
      }
        
        return view('admin.profile.edit', ['profile_form' => $profile]);
    }

    public function update(Request $request)
    {
       // Validationをかける
      $this->validate($request, profile::$rules);
      $profile = Profile::find($request->id);
      $profile_form = $request->all();
      unset($profile_form['_token']);

      // 該当するデータを上書きして保存する
      $profile->fill($profile_form)->save();  
      
      // 以下を追記
        $history = new profileHistory();
        $history->profile_id = $profile->id;
        $history->edited_at = Carbon::now();
        $history->save();
      
        return redirect('admin/profile/');
    } 
    
     // 以下を追記
  public function index(Request $request)
  {
      $cond_title = $request->cond_title;
      if ($cond_title != '') {
          // 検索されたら検索結果を取得する
          $posts = Profile::where('name', $cond_title)->get();
      } else {
          // それ以外はすべてのニュースを取得する
          $posts = Profile::all();
      }
      return view('admin.profile.index', ['posts' => $posts, 'cond_title' => $cond_title]);
  }

 // 以下を追記　　
  public function delete(Request $request)
  {
      // 該当するNews Modelを取得
      $profile = Profile::find($request->id);
      // 削除する
      $profile->delete();
      return redirect('admin/profile/');
  }  
  
}

