<?php

namespace App\Repository;

use App\Models\User;
use Illuminate\Support\Facades\DB;

class ArtistRepository
{

    public function getAllArtistLists()
    {
        return DB::table('users')
            ->join('artists', 'artists.user_id', '=', 'users.id')
            ->paginate(10);
    }
    public function findOrFailArtistById($id)
    {
        return DB::table('artists')
            ->join('users','users.id','=', 'artists.user_id')
            ->where('artists.user_id', $id)
            ->first();
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
                'role' => 'artist',
                'gender' => $data['gender'],
                'address' => $data['address'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            DB::table('artists')->insert([
                'user_id' => $userId,
                'first_release_year' => $data['first_release_year']
            ]);

            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return $e;
        }
    }

    public function update($artistDetail, $validatedData)
    {
        $userId = $artistDetail->id;

        $updateData = [
            'first_name' => $validatedData['first_name'],
            'last_name' => $validatedData['last_name'],
            'email' => $validatedData['email'],
            'dob' => $validatedData['dob'],
            'gender' => $validatedData['gender'],
            'address' => $validatedData['address'],
            'updated_at' => now(),
        ];
        try{
            DB::beginTransaction();

            DB::table('users')
                ->where('id', $userId)
                ->update($updateData);


            if (isset($validatedData['first_release_year']) && $validatedData['first_release_year']) {
                DB::table('artists')
                    ->where('user_id', $userId)
                    ->update([
                        'first_release_year' => $validatedData['first_release_year'],
                        'updated_at' => now(),
                    ]);
            }
            DB::commit();
        }catch(\Exception $e){
            DB::rollBack();
            return $e;
        }
    }

    public function updateNoOfAlbumsReleasedCount($artistId, $operation)
    {
        $currentCount = DB::selectOne("
            SELECT no_of_albums_released
            FROM artists
            WHERE user_id = :user_id",
            [
                'user_id' => $artistId
            ]);

        if (!$currentCount) {
            throw new \Exception('Artist Not found');
        }

        $currentCount = $currentCount->no_of_albums_released;

        if ($operation === 'add') {
            DB::update("
            UPDATE artists
            SET no_of_albums_released = no_of_albums_released + 1
            WHERE user_id = :user_id
        ", [
            'user_id' => $artistId
            ]);
        } elseif ($operation === 'subtract') {
            DB::update("
            UPDATE artists
            SET no_of_albums_released = no_of_albums_released - 1
            WHERE user_id = :user_id",
                [
                    'user_id' => $artistId
                ]);
        } else {
            throw new \Exception('Invalid operation');
        }
    }


}
