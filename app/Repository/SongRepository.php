<?php

namespace App\Repository;

use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class SongRepository
{

    public function getArtistAllSongs($artistId)
    {
        $query = "
        SELECT
            songs.id AS id,
            songs.title AS title,
            songs.album_name AS album_name,
            songs.genre AS genre,
            users.first_name AS first_name,
            users.last_name AS last_name
        FROM
            songs
        INNER JOIN
            artists ON artists.user_id = songs.artist_id
        INNER JOIN
            users ON users.id = artists.user_id
        WHERE
            songs.artist_id = :artistId
    ";

        return DB::select($query, ['artistId' => $artistId]);
    }

    public function findOrFailSongById($songId)
    {
        $song = DB::selectOne("
        SELECT *
        FROM songs
        WHERE id = :id",
            [
                'id' => $songId
            ]);
        if (!$song) {
            throw new ModelNotFoundException("Song with ID {$songId} not found.");
        }
        return $song;
    }

    public function create($data)
    {
        return DB::insert("
        INSERT INTO songs (artist_id, title, album_name, genre)
        VALUES (:artist_id, :title, :album_name, :genre, :created_at, :updated_at)",
            [
                'artist_id' => $data['artist_id'],
                'title' => $data['title'],
                'album_name' => $data['album_name'],
                'genre' => $data['genre'],
                'created_at' => now(),
                'updated_at' => now()

            ]);
    }

    public function update($songId, $validatedData)
    {
        $query = "UPDATE songs
                    SET title = :title,
                        album_name = :album_name,
                        genre = :genre,
                        updated_at = :updated_at
                    WHERE id = :id
                ";

        $updated = DB::update($query, [
            'title' => $validatedData['title'],
            'album_name' => $validatedData['album_name'],
            'genre' => $validatedData['genre'],
            'updated_at' => now(),
            'id' => $songId
        ]);

        if ($updated === 0) {
            throw new Exception('Song not found or no changes made.');
        }

        return $updated;
    }


    public function delete($songId)
    {
        $query = "DELETE FROM songs WHERE id = :id";
        $deleted = DB::delete($query, ['id' => $songId]);
        if ($deleted === 0) {
            throw new Exception('Song not found or already deleted.');
        }
        return $deleted;
    }


}
