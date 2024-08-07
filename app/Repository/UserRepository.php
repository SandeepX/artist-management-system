<?php

namespace App\Repository;

use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function getAllUserLists($select=['*'])
    {
        return DB::table('users')->select($select)->paginate(5);
    }

    public function findOrFailUserById($userId, $select = ['*'])
    {
        $user = DB::table('users')->select($select)->where('id', $userId)->first();

        if (!$user) {
            abort(404, 'User not found.');
        }

        return $user;
    }

    public function create($data)
    {
        try{
            $userId = DB::table('users')->insertGetId([
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'dob' => $data['dob'],
                'role' => $data['role'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'phone' => $data['phone'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            if ($data['role'] == 'artist') {
                DB::table('artists')->insert([
                    'user_id' => $userId
                ]);
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return $e;
        }

    }

    public function update($userDetail,$validatedData)
    {
        $userId = $userDetail->id;

        $updateData = [
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'dob' => $validatedData['dob'],
            'role' => $validatedData['role'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'],
            'phone' => $validatedData['phone'],
            'updated_at' => now(),
        ];
        try{
            DB::beginTransaction();

            DB::table('users')
                ->where('id', $userId)
                ->update($updateData);

            if($validatedData['role'] == 'artist'){
                $user = DB::table('artists')->where('user_id', $userId)->first();

                if(!$user){
                    DB::table('artists')->insert([
                        'user_id' => $userId
                    ]);
                }
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return $e;
        }


    }

    public function delete($userId)
    {
        $user = DB::table('users')->where('id', $userId)->first();
        if (!$user) {
            abort(404, 'User not found.');
        }
        return DB::table('users')->where('id', $userId)->delete();
    }

}
