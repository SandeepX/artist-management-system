<?php

namespace App\Repository;

use App\Helper\PaginationHelper;
use Illuminate\Support\Facades\DB;

class UserRepository
{
    public function getAllUserLists($select = ['*'])
    {
        $columns = implode(', ', $select);

        $query = "SELECT $columns FROM users ORDER BY created_at DESC";
        $totalQuery = "SELECT COUNT(*) as total FROM users";

        return PaginationHelper::paginateRawQuery($query, [], 5, null, $totalQuery);
    }


    public function findOrFailUserById($userId, $select = ['*'])
    {
        $columns = implode(', ', $select);

        $query = "SELECT $columns FROM users WHERE id = :id";

        $user = DB::selectOne($query, ['id' => $userId]);

        if (!$user) {
            abort(404, 'User not found.');
        }

        return $user;
    }


    public function create($data)
    {
        DB::beginTransaction();
        try {
            DB::statement("INSERT INTO users (
                   first_name,
                   last_name,
                   email,
                   password,
                   dob,
                   role,
                   gender,
                   address,
                   phone,
                   created_at,
                   updated_at
                   )VALUES(
                            :first_name,
                            :last_name,
                            :email,
                            :password,
                            :dob,
                            :role,
                            :gender,
                            :address,
                            :phone,
                            :created_at,
                            :updated_at
                            )",
                [
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

            $userId = DB::selectOne("SELECT LAST_INSERT_ID() as id")->id;

            if ($data['role'] == 'artist') {
                DB::statement("INSERT INTO artists (user_id) VALUES (:user_id)",
                    [
                        'user_id' => $userId
                    ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
    public function update($userDetail, $validatedData)
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

        $updateQuery = "
            UPDATE users
            SET first_name = :first_name,
                last_name = :last_name,
                email = :email,
                dob = :dob,
                role = :role,
                gender = :gender,
                address = :address,
                phone = :phone,
                updated_at = :updated_at
            WHERE id = :id
        ";

        $insertArtistQuery = "INSERT INTO artists (user_id) VALUES (:user_id)";

        $selectArtistQuery = "SELECT 1 FROM artists WHERE user_id = :user_id";

        DB::beginTransaction();

        try {
            DB::statement($updateQuery, array_merge(['id' => $userId], $updateData));

            if ($validatedData['role'] == 'artist') {
                $artistExists = DB::selectOne($selectArtistQuery, ['user_id' => $userId]);

                if (!$artistExists) {
                    DB::statement($insertArtistQuery, ['user_id' => $userId]);
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }


    public function delete($userId)
    {
        $userExists = DB::selectOne("SELECT 1 FROM users WHERE id = :id", ['id' => $userId]);

        if (!$userExists) {
            abort(404, 'User not found.');
        }

        $query = "DELETE FROM users WHERE id = :id";

        $deleted = DB::delete($query, ['id' => $userId]);

        if ($deleted === 0) {
            throw new \Exception('Something went wrong.');
        }

        return $deleted;
    }

}
