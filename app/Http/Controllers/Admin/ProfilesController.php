<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Profile;
use App\LookupData;
use App\User;
use Auth;
use Illuminate\Support\Facades\Storage;

class ProfilesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user_info = Auth::user();
        $user_type = config('static_arrays.agent_type');
        $user_groups = LookupData::where('lookup_pk_no', $user_info->role)->get();
        if (!empty($user_groups)) {
            foreach ($user_groups as $group) {
                $user_group[$group->lookup_pk_no] = $group->lookup_name;
            }
        }
        $user = User::where('id',$user_info->id)->first();
        return view('admin.profile.profile', compact('user','user_group','user_type'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $dob=\Carbon\Carbon::parse($request->dob)->format('Y-m-d');
//        return $dob;


        if(Auth::User()->profile) {
            $profile = Auth::User()->profile;
            $profile->city_id = $request->city;
            $profile->country_id = $request->country;
            $profile->address = $request->address;
            $profile->phone = $request->phone;
            $profile->gender = $request->gender;
            $profile->dob = $dob;


            if ($request->hasFile('image')) {
                if($profile->image)
                {
                    Storage::delete(asset($profile->image));

                }
                $image = $request->image;
                $image_new = time() . '_' . Auth::id() . '_' . $image->getClientOriginalName();
                $image->move('uploads/image', $image_new);
                $profile->image = '/uploads/image/' . $image_new;
            }
            $profile->save();

        }
        else{
            $profile = Profile::create([
                'city_id'       => $request->city,
                'country_id'    => $request->country,
                'user_id'       => Auth::id(),
                'address'       => $request->address,
                'phone'         => $request->phone,
                'gender'        => $request->gender,
                'dob'           => $dob
            ]);

            $image = $request->image;
            $image_new_name = time().'_'.$request->user_id.'_'.$image->getClientOriginalName();
            $profile->image = '/uploads/image/'.$image_new_name;
            $image->move('uploads/image', $image_new_name);
            $profile->save();

        }
        return redirect()->route('profile');


    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
