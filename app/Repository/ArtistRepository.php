<?php

namespace App\Repository;

use App\Helper\PaginationHelper;
use Illuminate\Support\Facades\DB;

class ArtistRepository
{
    public function getAllArtistLists()
    {
        $query = "
            SELECT users.*, artists.*
            FROM users
            JOIN artists ON artists.user_id = users.id
            ORDER BY users.created_at DESC
        ";

        $totalQuery = "SELECT COUNT(*) as total FROM users JOIN artists ON artists.user_id = users.id";

        return PaginationHelper::paginateRawQuery($query, [],  3, null, $totalQuery);
    }

    public function findOrFailArtistById($id)
    {
        $query = "SELECT
                        artists.*,
                        users.*
                    FROM
                        artists
                            JOIN  users ON users.id = artists.user_id
                    WHERE artists.user_id = :id
                    LIMIT 1";

        $artist = DB::selectOne($query, ['id' => $id]);

        if (!$artist) {
            abort(404, 'Artist not found.');
        }

        return $artist;
    }

    public function create($data)
    {
        try {
            DB::beginTransaction();

            $userQuery = "
                INSERT INTO users (first_name, last_name, email, password, dob, role, gender, address, created_at, updated_at)
                VALUES (:first_name, :last_name, :email, :password, :dob, 'artist', :gender, :address, :created_at, :updated_at)
            ";

            DB::insert($userQuery, [
                'first_name' => $data['first_name'],
                'last_name' => $data['last_name'],
                'email' => $data['email'],
                'password' => bcrypt($data['password']),
                'dob' => $data['dob'],
                'gender' => $data['gender'],
                'address' => $data['address'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $userId = DB::getPdo()->lastInsertId();

            $artistQuery = "INSERT INTO artists (user_id, first_release_year) VALUES (:user_id, :first_release_year)";

            DB::insert($artistQuery, [
                'user_id' => $userId,
                'first_release_year' => $data['first_release_year'],
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
    public function update($artistDetail, $validatedData)
    {
        $userId = $artistDetail->id;

        try {
            DB::beginTransaction();

            $userQuery = "
                UPDATE users
                SET first_name = :first_name,
                    last_name = :last_name,
                    email = :email,
                    dob = :dob,
                    gender = :gender,
                    address = :address,
                    updated_at = :updated_at
                WHERE id = :id
            ";

            DB::update($userQuery, [
                'first_name' => $validatedData['first_name'],
                'last_name' => $validatedData['last_name'],
                'email' => $validatedData['email'],
                'dob' => $validatedData['dob'],
                'gender' => $validatedData['gender'],
                'address' => $validatedData['address'],
                'updated_at' => now(),
                'id' => $userId,
            ]);

            if (isset($validatedData['first_release_year']) && $validatedData['first_release_year']) {

                $artistQuery = "UPDATE artists SET first_release_year = :first_release_year,updated_at = :updated_at WHERE user_id = :user_id";

                DB::update($artistQuery, [
                    'first_release_year' => $validatedData['first_release_year'],
                    'updated_at' => now(),
                    'user_id' => $userId,
                ]);
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return $e;
        }
    }
    public function updateNoOfAlbumsReleasedCount($artistId, $operation)
    {
        $currentCount = DB::selectOne("SELECT no_of_albums_released FROM artists WHERE user_id = :user_id", [
                'user_id' => $artistId
        ]);

        if (!$currentCount) {
            throw new \Exception('Artist Not found');
        }

        $currentCount = $currentCount->no_of_albums_released;

        if ($operation === 'add') {
            DB::update("UPDATE artists SET no_of_albums_released = $currentCount + 1 WHERE user_id = :user_id",
                [
                    'user_id' => $artistId
                ]
            );
        } elseif ($operation === 'subtract') {
            DB::update("UPDATE artists SET no_of_albums_released = $currentCount - 1 WHERE user_id = :user_id",
                [
                    'user_id' => $artistId
                ]
            );
        } else {
            throw new \Exception('Invalid operation');
        }
    }
}
