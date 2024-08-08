<?php

namespace App\Http\Controllers;

use App\Http\Requests\ArtistRequest;
use App\Models\User;
use App\Repository\ArtistRepository;
use App\Repository\UserRepository;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ArtistController extends Controller
{
    private $view = 'artist.';
    private ArtistRepository $artistRepo;

    public function __construct(ArtistRepository $artistRepository)
    {
        $this->artistRepo = $artistRepository;
    }

    public function index()
    {
        $artists = $this->artistRepo->getAllArtistLists();
        return view($this->view.'index',compact('artists'));
    }


    public function create()
    {
        try{
            return view($this->view.'create');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }


    public function store(ArtistRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $this->artistRepo->create($validatedData);
            return redirect()
                ->route('artists.index')
                ->with('success', 'Artist Created Successfully');
        } catch (Exception $exception) {
            return redirect()
                ->back()
                ->with('danger', $exception->getMessage())
                ->withInput();
        }
    }



    public function edit($artistId)
    {
        try {
            $artistDetail = $this->artistRepo->findOrFailArtistById($artistId);
            return view($this->view.'edit',compact('artistDetail'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ArtistRequest $request, $artistId)
    {
        try {
            $validatedData = $request->validated();
            $artistDetail = $this->artistRepo->findOrFailArtistById($artistId);
            $this->artistRepo->update($artistDetail,$validatedData);
            return redirect()
                ->route('artists.index')
                ->with('success', 'Artist Detail Updated Successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($artistId)
    {
        try{
            (new UserRepository())->delete($artistId);
            return redirect()
                ->back()
                ->with('success', 'Artist Detail Deleted Successfully');
        }catch(Exception $exception){
            return redirect()
                ->back()
                ->with('danger', $exception->getMessage());
        }
    }

    public function exportArtists()
    {
        try{
            $artists = DB::table('artists')
                ->join('users', 'artists.user_id', '=', 'users.id')
                ->select(
                    'users.first_name',
                    'users.last_name',
                    'users.dob',
                    'users.gender',
                    'users.address',
                    'artists.first_release_year',
                    'artists.no_of_albums_released'
                )
                ->get();

            $csvData = [];

            $csvData[] = ['Name', 'Name', 'DOB', 'Gender', 'Address', 'First Release Year', 'No of Albums Released'];

            foreach ($artists as $artist) {
                $csvData[] = [
                    $artist->first_name.' ' .$artist->last_name,
                    $artist->last_name,
                    $artist->dob,
                    User::GENDER[$artist->gender],
                    $artist->address,
                    $artist->first_release_year,
                    $artist->no_of_albums_released,
                ];
            }

            $filename = 'artists_' . now()->format('Y-m-d_H-i-s') . '.csv';
            $filePath = storage_path('app/' . $filename);

            $file = fopen($filePath, 'w');
            foreach ($csvData as $row) {
                fputcsv($file, $row);
            }
            fclose($file);

            return response()->download($filePath, $filename)->deleteFileAfterSend();

        }catch(Exception $e){
            return redirect()->back()->with('danger', 'Error Exporting CSV: ' . $e->getMessage());
        }

    }


    public function importArtists(Request $request)
    {
        $file = $request->file('csv_file');

        if ($file->isValid()) {
            $filePath = $file->getRealPath();
            $handle = fopen($filePath, 'r');
            $header = true;

            DB::beginTransaction();

            try {
                while (($row = fgetcsv($handle, 1000, ',')) !== false) {
                    if ($header) {
                        $header = false;
                        continue;
                    }

                    if (empty($row[2]) || DB::table('users')->where('email', $row[2])->exists()) {
                        continue;
                    }

                    $dob = !empty($row[3]) && strtotime($row[3]) ? $row[3] : null;
                    $releaseYear = !empty($row[6]) && strtotime($row[6]) ? $row[6] : null;

                    $userId = DB::table('users')->insertGetId([
                        'first_name' => $row[0] ?? null,
                        'last_name' => $row[1] ?? null,
                        'email' => $row[2],
                        'dob' => $dob,
                        'gender' => !empty($row[4]) ? $row[4] : 'm',
                        'address' => $row[5] ?? null,
                        'role' => 'artist',
                        'password' => bcrypt('artist123'),
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);

                    DB::table('artists')->insert([
                        'user_id' => $userId,
                        'first_release_year' => $releaseYear,
                        'created_at' => now(),
                        'updated_at' => now(),
                    ]);
                }

                DB::commit();
                return redirect()->route('artists.index')->with('success', 'CSV Imported Successfully.');
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()->with('danger', 'Error Importing CSV: ' . $e->getMessage());
            }
        }
        return redirect()->back()->with('danger', 'Invalid File.');
    }


}
