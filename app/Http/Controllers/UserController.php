<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserRequest;
use App\Repository\UserRepository;
use Exception;

class UserController extends Controller
{
    private $view = 'users.';

    private $userRepo;

    public function __construct(UserRepository $userRepository){
        $this->userRepo = $userRepository;
    }

    public function index()
    {
        $users = $this->userRepo->getAllUserLists();
        return view($this->view.'index',compact('users'));
    }

    public function create()
    {
        try{
            return view($this->view.'create');
        }catch(Exception $exception){
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function store(UserRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $this->userRepo->create($validatedData);
            return redirect()
                ->route('users.index')
                ->with('success', 'User Created Successfully');
        } catch (Exception $exception) {
            return redirect()
                ->back()
                ->with('danger', $exception->getMessage())
                ->withInput();
        }
    }

    public function edit($userId)
    {
        try {
            $userDetail = $this->userRepo->findOrFailUserById($userId);
            return view($this->view.'edit',compact('userDetail'));
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function update(UserRequest $request, $id)
    {
        try {
            $validatedData = $request->validated();
            $userDetail = $this->userRepo->findOrFailUserById($id);
            $this->userRepo->update($userDetail,$validatedData);
            return redirect()
                ->route('users.index')
                ->with('success', 'User Detail Updated Successfully');
        } catch (\Exception $exception) {
            return redirect()->back()->with('danger', $exception->getMessage());
        }
    }

    public function delete($userId)
    {
        try{
            $this->userRepo->delete($userId);
            return redirect()
                ->back()
                ->with('success', 'User Detail Deleted Successfully');
        }catch(Exception $exception){
            return redirect()
                ->back()
                ->with('danger', $exception->getMessage());
        }
    }
}
