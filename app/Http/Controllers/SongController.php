<?php

namespace App\Http\Controllers;

use App\Http\Requests\SongRequest;
use App\Repository\ArtistRepository;
use App\Repository\SongRepository;
use Exception;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class SongController extends Controller
{

    private $view = 'song.';
    private  $musicRepo;

    public function __construct(SongRepository $songRepository)
    {
        $this->musicRepo = $songRepository;
    }

    public function index()
    {
        $artistId = Auth::id();
        if(Auth::user()->role != 'artist'){
            return redirect()->back();
        }
        return $this->renderArtistSongsView($artistId);
    }

    public function getSongsByArtistId($artistId)
    {
        return $this->renderArtistSongsView($artistId);
    }

    private function renderArtistSongsView($artistId)
    {
        $songs = $this->musicRepo->getArtistAllSongs($artistId);

        $artistDetail = DB::selectOne("
            SELECT id, first_name, last_name
            FROM users
            WHERE id = :id", ['id' => $artistId]);

        return view($this->view . 'index', compact('artistDetail', 'songs'));
    }

    public function create($artistId)
    {
        try{
            return view($this->view.'create', compact('artistId'));
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function store(SongRequest $request)
    {
        try {
            $validatedData = $request->validated();
            DB::beginTransaction();
                $this->musicRepo->create($validatedData);
                (new ArtistRepository())->updateNoOfAlbumsReleasedCount($validatedData['artist_id'],'add');
                DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Song Created Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('danger', $exception->getMessage())
                ->withInput();
        }
    }

    public function edit($songId)
    {
        try {
            $songDetail = $this->musicRepo->findOrFailSongById($songId);
            $artistId = $songDetail->artist_id;
            return view($this->view.'edit',compact('songDetail', 'artistId'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }


    public function update(SongRequest $request, $songId)
    {
        try {
            $validatedData = $request->validated();
            $this->musicRepo->update($songId,$validatedData);
            return redirect()
                ->back()
                ->with('success', 'Song Detail Updated Successfully');
        } catch (Exception $exception) {
            DB::rollBack();
            return redirect()
                ->back()
                ->with('danger', $exception->getMessage())
                ->withInput();
        }
    }


    public function delete($songId)
    {
        try{
            $songDetail = $this->musicRepo->findOrFailSongById($songId);
            DB::beginTransaction();

            $this->musicRepo->delete($songId);

            (new ArtistRepository())->updateNoOfAlbumsReleasedCount(
                artistId: $songDetail->artist_id,
                operation: 'subtract'
            );

            DB::commit();
            return redirect()
                ->back()
                ->with('success', 'Artist song  deleted');
        }catch(Exception $exception){
            DB::rollBack();
            return redirect()
                ->back()
                ->with('danger', $exception->getMessage());
        }
    }
}
